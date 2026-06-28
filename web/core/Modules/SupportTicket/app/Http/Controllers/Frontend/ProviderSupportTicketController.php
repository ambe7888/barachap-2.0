<?php

namespace Modules\SupportTicket\app\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\SupportTicket\app\Models\ChatMessage;
use Modules\SupportTicket\app\Models\Department;
use Modules\SupportTicket\app\Models\Ticket;
use App\Mail\BasicMail;
use App\Helpers\FlashMsg;
use App\Models\User;
use App\Models\UserNotification;

class ProviderSupportTicketController extends Controller
{
    public function ticket(Request $request)
    {
        $user_id=Auth::user()->id;

        if($request->isMethod('post')){
           
            $user = User::where('id',$user_id)->first();
            $request->validate([
                'title'=> 'required|max:191',
                'department'=> 'required|max:191',
                'priority'=> 'required',
                'description'=> 'required',
            ]);

            // create ticket for specific user
            $ticket = Ticket::create([
                'department_id'=>$request->department,
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

            return back()->with((FlashMsg::item_new('New Ticket Successfully Added')));
        }
        $tickets = Ticket::where("user_id",$user_id)->whereHas('user')->latest()->paginate(10);
        $departments = Department::all();
        $users = User::select(['id','first_name','last_name','username'])->get();
        return view('supportticket::frontend.ticket.tickets',compact(['tickets','departments','users']));
    }

    //paginate
    public function paginate(Request $request)
    {
        $user_id=Auth::user()->id;
        if($request->ajax()){
            if(empty($request->string_search)){
                $tickets = Ticket::where("user_id",$user_id)->whereHas('user')->latest()->paginate(10);
            }else{
                $tickets = Ticket::where("user_id",$user_id)->where(function($q) use ($request){
                    $q->orWhere('id', $request->string_search)
                        ->orWhere('priority',$request->string_search)
                        ->orWhere('status',$request->string_search);
                })->latest()->paginate(10);
            }
            return $tickets->total() >= 1
                ? view('supportticket::frontend.ticket.search-result', compact('tickets'))->render()
                : response()->json(['status'=>__('nothing')]);
        }
    }

    //search
    public function search_ticket(Request $request)
    {
        $searchTerm = strip_tags($request->string_search);
        $priority = $request->priority;
        $status = $request->status;
        $user_id=Auth::user()->id;

        // Query tickets with filters and search term
        $tickets = Ticket::where("user_id",$user_id)->where(function ($query) use ($searchTerm, $priority, $status) {
            if ($searchTerm) {
                $query->where('id', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('priority', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('status', 'LIKE', "%{$searchTerm}%");
            }

            if ($priority) {
                $query->where('priority', $priority);
            }

            if ($status) {
                $query->where('status', $status);
            }
        })
            ->whereHas('user')
            ->latest()
            ->paginate(10);

        return $tickets->total() >= 1
            ? view('supportticket::frontend.ticket.search-result', compact('tickets'))->render()
            : response()->json(['status'=>__('nothing')]);
    }

    public function ticket_details(Request $request, $id,$notificationId=null){
        $ticket_details = Ticket::with('user','admin','message','get_ticket_latest_message')->where('id',$id)->first();
        if($request->isMethod('post')){
            // user to admin ticket chat
            if(empty($request->attachment) && empty($request->message)){
                $request->validate([
                    'message'=> 'required|max:10000',
                ]);
            }

            if(!empty($request->attachment) || empty($request->message)){
                $request->validate([
                    'attachment'=> 'mimes:jpg,jpeg,png,gif,pdf,svg,xlsx,xls,txt,webp',
                ]);
            }

            if($attachment = $request->file('attachment')){
                $imageName = time().'-'.uniqid().'.'.$attachment->getClientOriginalExtension();
                $attachment->move('assets/uploads/ticket/chat-messages',$imageName);
            }

            
            $user_id = Auth::guard('sanctum')->user()->id;
            ChatMessage::create([
                'ticket_id'=> $id,
                'message'=>$request->message,
                'attachment'=> $imageName ?? '',
                'notify'=> $request->email_notify,
                'type'=> 'user',
            ]);

            // send notification to user
            notificationToAdmin($id, $ticket_details?->user?->id,'ticket',__('Ticket New Message'));

            // email sent to user
            if($request->email_notify == 'on'){
                try {
                    $message = get_static_option('support_ticket_message_email_message') ?? __('Support Ticket Message Email Notify');
                    $message = str_replace(["@name","@ticket_id"],[__('Admin') ,$id], $message);
                    $subject = get_static_option('support_ticket_message_email_subject') ?? __('Support Ticket Message Email');

                    Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                        'subject' => $subject,
                        'message' => $message
                    ]));
                } catch (\Exception $e) {}
            }

            return back();
        }

        try {
            UserNotification::where('id', $notificationId)->update(['is_read' => 'read']);
        }catch (\Exception $e) {}

        return !empty($ticket_details) ? view('supportticket::frontend.ticket.details',compact('ticket_details')) : back();
    }

}
