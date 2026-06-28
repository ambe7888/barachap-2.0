<style>
    .queue-order {
        border-color: #f2f2f2;
        border-left: 3px solid #e0a800;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 10px;
        margin-top: 2px;
    }
    .active-order, .complete-order {
        border-color: #f2f2f2;
        border-left: 3px solid #3aad3a;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 10px;
        margin-top: 2px;
    }
    .deliver-order {
        border-color: #f2f2f2;
        border-left: 3px solid #33BBC5;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 10px;
        margin-top: 2px;
    }
    .cancel-order, .decline-order {
        border-color: #f2f2f2;
        border-left: 3px solid #dd0000;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 10px;
        margin-top: 2px;
    }
    .cancel-order {
        border-color: #f2f2f2;
        border-left: 3px solid #cb801e;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 10px;
        margin-top: 2px;
    }
    .approve-order {
        border-color: #f2f2f2;
        border-left: 3px solid #cb801e;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 10px;
        margin-top: 2px;
    }
</style>

@if($status === 0)
    <span class="queue-order" >{{__('Pending')}}</span>
@elseif($status === 1)
    <span class="approve-order" >{{__('Approve')}}</span>
@elseif($status === 2)
    <span class="complete-order" >{{__('Complete')}}</span>
@elseif($status === 3)
    <span class="cancel-order" >{{__('Cancel')}}</span>
@endif
