<?php

namespace Modules\SupportTicket\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\SupportTicket\app\Models\ChatMessage;
use Modules\SupportTicket\app\Models\Department;
use Modules\SupportTicket\app\Models\Ticket;
use Modules\SupportTicket\app\Resources\DepartmentResource;
use Modules\SupportTicket\app\Resources\SupportTicketDetailsResource;
use Modules\SupportTicket\app\Resources\SupportTicketMessageResource;
use Modules\SupportTicket\app\Resources\SupportTicketResource;

class ClientSupportTicketController extends Controller
{

    public function allDepartment(){

        $departments = Department::latest()->paginate(10);
        if (empty($departments)){
            return response()->json([
                'message' => __('support ticket not yet.')
            ], 200);
        }

        if ($departments->isNotEmpty()) {
            return response()->json([
                'departments' => DepartmentResource::collection($departments->items()),
                'pagination' => [
                    'total' => $departments->total(),
                    'count' => $departments->count(),
                    'per_page' => $departments->perPage(),
                    'current_page' => $departments->currentPage(),
                    'last_page' => $departments->lastPage(),
                    'next_page_url' => $departments->nextPageUrl(),
                    'prev_page_url' => $departments->previousPageUrl(),
                ]
            ], 200);
        }
    }

    public function createTicket(Request $request)
    {

        $user_id = Auth::guard('sanctum')->user()->id;

            $request->validate([
                'title'=> 'required|max:191',
                'department_id'=> 'required|max:191',
                'priority'=> 'required|max:191',
                'description'=> 'required',
            ]);

            // create ticket for specific user
            $ticket = Ticket::create([
                'department_id'=>$request->department_id,
                'user_id'=> $user_id,
                'title'=>$request->title,
                'priority'=>$request->priority,
                'description'=>$request->description,
                'status' => 'open',
            ]);

            // send notification to admin
            notificationToAdmin($ticket->id, $user_id,'ticket',__('New Support Ticket'));

            //Email to admin
            try {
                $subject = get_static_option('support_ticket_subject') ?? __('Support Ticket');
                $message = get_static_option('support_ticket_message') ?? __('Support Ticket Message');
                $message = str_replace(["@name","@ticket_id"],[__('Admin'),$ticket->id], $message);

                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => $subject,
                    'message' => $message
                ]));

            } catch (\Exception $e) {}

            return response()->json([
                'message' => __('New Ticket Successfully Added'),
            ], 200);

    }


    public function allTickets(Request $request)
    {

        $user_id = Auth::guard('sanctum')->user()->id;
        $tickets = Ticket::with('department')->where('user_id', $user_id)
            ->latest()
            ->paginate(10);

            if ($tickets->isNotEmpty()) {
                return response()->json([
                    'tickets' => SupportTicketResource::collection($tickets->items()),
                    'pagination' => [
                        'total' => $tickets->total(),
                        'count' => $tickets->count(),
                        'per_page' => $tickets->perPage(),
                        'current_page' => $tickets->currentPage(),
                        'last_page' => $tickets->lastPage(),
                        'next_page_url' => $tickets->nextPageUrl(),
                        'prev_page_url' => $tickets->previousPageUrl(),
                    ]
                ]);
            }

        return response()->json([
            'message' => __('Ticket not yet available'),
        ], 200);
    }

    public function viewTickets($id)
    {
        $user_id = Auth::guard('sanctum')->user()->id;
        $ticket_details = Ticket::with('user','department')
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->first();

        if (!empty($ticket_details)) {
            // Paginate the messages
            $messages = $ticket_details->message()->paginate(10);
            return response()->json([
                'ticket_details' => new SupportTicketDetailsResource($ticket_details),
                'messages' => SupportTicketMessageResource::collection($messages),
                'pagination' => [
                    'total' => $messages->total(),
                    'per_page' => $messages->perPage(),
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                    'next_page_url' => $messages->nextPageUrl(),
                    'prev_page_url' => $messages->previousPageUrl(),
                ]
            ]);
        }else {
            return response()->json([
                'message' => __('Ticket not found or you do not have permission to view it.')
            ], 404);
        }
    }

    public function sendMessage(Request $request){

            if(empty($request->attachment) && empty($request->message)){
                $request->validate([
                    'message'=> 'required|max:10000',
                ]);
            }

            if(!empty($request->attachment) || empty($request->message)){
                $request->validate([
                    'attachment'=> 'nullable|mimes:jpg,jpeg,png,webp,gif,pdf,svg,xlsx,xls,txt',
                ]);
            }

            if($attachment = $request->file('attachment')){
                $imageName = time().'-'.uniqid().'.'.$attachment->getClientOriginalExtension();
                $attachment->move('assets/uploads/ticket/chat-messages',$imageName);
            }

           $user_id = Auth::guard('sanctum')->user()->id;
           $ticket_id = $request->ticket_id;

         ChatMessage::create([
                'ticket_id'=> $ticket_id,
                'message'=>$request->message,
                'attachment'=> $imageName ?? '',
                'notify'=> $request->email_notify,
                'type'=> 'user',
            ]);

             $ticket_details = Ticket::where('id', $ticket_id)
                 ->where('user_id', $user_id)
                 ->first();

            // send notification to user
            notificationToAdmin($ticket_id, $ticket_details?->user?->id,'ticket',__('Ticket New Message'));

            // email sent to user
            if($request->email_notify == 'on'){
                try {
                    $message = get_static_option('support_ticket_message_email_message') ?? __('Support Ticket Message Email Notify');
                    $message = str_replace(["@name","@ticket_id"],[__('Admin') ,$ticket_id], $message);
                    $subject = get_static_option('support_ticket_message_email_subject') ?? __('Support Ticket Message Email');

                    Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                        'subject' => $subject,
                        'message' => $message
                    ]));
                } catch (\Exception $e) {}
            }

            $ticket_message_lists = ChatMessage::where('ticket_id', $ticket_id)
                ->latest()
                ->paginate(10);

            return response()->json([
                'message'=>__('Message Send Success'),
                'ticket_message_lists' => SupportTicketMessageResource::collection($ticket_message_lists->items()),
                'pagination' => [
                    'total' => $ticket_message_lists->total(),
                    'count' => $ticket_message_lists->count(),
                    'per_page' => $ticket_message_lists->perPage(),
                    'current_page' => $ticket_message_lists->currentPage(),
                    'last_page' => $ticket_message_lists->lastPage(),
                    'next_page_url' => $ticket_message_lists->nextPageUrl(),
                    'prev_page_url' => $ticket_message_lists->previousPageUrl(),
                ]
            ]);

    }

}
