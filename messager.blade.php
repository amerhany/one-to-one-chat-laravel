@extends('layouts.app')
@section('content')
<div class="container con-t">
    <div class="row">
    <nav class="menu">
        <ul class="items">
        <li class="item">
            <i class="fa fa-home" aria-hidden="true"></i>
        </li>
        <li class="item">
            <i class="fa fa-user" aria-hidden="true"></i>
        </li>
        <li class="item">
            <i class="fa fa-pencil" aria-hidden="true"></i>
        </li>
        <li class="item item-active">
            <i class="fa fa-commenting" aria-hidden="true"></i>
        </li>
        <li class="item">
            <i class="fa fa-file" aria-hidden="true"></i>
        </li>
        <li class="item">
            <i class="fa fa-cog" aria-hidden="true"></i>
        </li>
        </ul>
    </nav>

    <section class="discussions">
        <div class="discussion search">
        <div class="searchbar">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input type="text" placeholder="Search...">
        </div>
        </div>
        <div class="discussion message-active">
        <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);">
            <div class="online"></div>
        </div>
        <div class="desc-contact">
            <p class="name">Megan Leib</p>
            <p class="message">9 pm at the bar if possible 😳</p>
        </div>
        <div class="timer">12 sec</div>
        </div>

        <div class="discussion">
        <div class="photo" style="background-image: url(https://i.pinimg.com/originals/a9/26/52/a926525d966c9479c18d3b4f8e64b434.jpg);">
            <div class="online"></div>
        </div>
        <div class="desc-contact">
            <p class="name">Dave Corlew</p>
            <p class="message">Let's meet for a coffee or something today ?</p>
        </div>
        <div class="timer">3 min</div>
        </div>

        <div class="discussion">
        <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1497551060073-4c5ab6435f12?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=667&q=80);">
        </div>
        <div class="desc-contact">
            <p class="name">Jerome Seiber</p>
            <p class="message">I've sent you the annual report</p>
        </div>
        <div class="timer">42 min</div>
        </div>

        <div class="discussion">
        <div class="photo" style="background-image: url(https://card.thomasdaubenton.com/img/photo.jpg);">
            <div class="online"></div>
        </div>
        <div class="desc-contact">
            <p class="name">Thomas Dbtn</p>
            <p class="message">See you tomorrow ! 🙂</p>
        </div>
        <div class="timer">2 hour</div>
        </div>

        <div class="discussion">
        <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1553514029-1318c9127859?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=700&q=80);">
        </div>
        <div class="desc-contact">
            <p class="name">Elsie Amador</p>
            <p class="message">What the f**k is going on ?</p>
        </div>
        <div class="timer">1 day</div>
        </div>

        <div class="discussion">
        <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1541747157478-3222166cf342?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=967&q=80);">
        </div>
        <div class="desc-contact">
            <p class="name">Billy Southard</p>
            <p class="message">Ahahah 😂</p>
        </div>
        <div class="timer">4 days</div>
        </div>

        <div class="discussion">
        <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1435348773030-a1d74f568bc2?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1050&q=80);">
            <div class="online"></div>
        </div>
        <div class="desc-contact">
            <p class="name">Paul Walker</p>
            <p class="message">You can't see me</p>
        </div>
        <div class="timer">1 week</div>
        </div>
    </section>

    <section class="chat">
        <div class="header-chat">
        <i class="icon fa fa-user-o" aria-hidden="true"></i>
        <p class="name">{{$user->name}}
            <span id="status">Offline</span>

        {{-- @if ($user->is_online == 1)
            <span id="status">Online</span>
        @else
            <span id="status">Offline</span>
        @endif --}}
        </p>
        <i class="icon clickable fa fa-ellipsis-h right" aria-hidden="true"></i>
        </div>
        <div class="messages-chat" id="container-message">

            @foreach ($messages as $message)

                @if ($message->from->id == auth()->user()->id)
                <?php
                    $seen = 'fa-sharp fa-solid fa-check-double unreaded';
                ?>
                @if ($message->is_readed == 1)
                    <?php
                        $seen = 'fa-sharp fa-solid fa-check-double readed';
                    ?>
                @endif
                <div class="message text-only">
                    <div class="response">
                        <p class="text">{{$message->message}}</p>

                    </div>
                </div>
                <p class="time my_time">{{$message->created_at->format('h:i:s A')}} <i class="{{$seen}}"></i></p>
                @else
                    <div class="message">
                        <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);">
                        <div class="online"></div>
                        </div>
                        <p class="text">{{$message->message}} </p>
                    </div>
                    <p class="time">{{$message->created_at->format('h:i:s A')}}</p>
                @endif
            @endforeach




        </div>
        <p id="typing"></p>
        <div class="footer-chat">
        <i class="icon fa-regular fa-face-smile clickable" style="font-size:25pt;"></i>
        <form class="w-100" id="form">
            <input type="text" class="write-message" id="input-message" placeholder="Type your message here">
            <input type="text" id="touserId" value="{{$user->id}}" hidden>
            <input type="text" hidden id="room-id" value="{{$room_id}}">
            <input type="submit" hidden>
        </form>
        <i class="icon send fa-solid fa-paper-plane clickable" id="send-btn"></i>
        </div>
    </section>
    </div>
</div>
@endsection
@section('script')
@vite(['resources/js/app.js'])
<script type="module">

    let token = $('meta[name="csrf-token"]').attr('content');
    let form = document.getElementById('form');
    let inputMessage = document.getElementById('input-message');

    let container = document.getElementById('container-message');

    let roomid = document.getElementById('room-id');
    const roomId = roomid.value;

    let touserId =document.getElementById('touserId');
    const toUserId = touserId.value;


    let send = document.getElementById('send-btn');
    let typing = document.getElementById('typing');

    let status = document.getElementById('status');

    let read_message = document.querySelectorAll('.fa-check-double.unreaded');







    let usersOnline = [];
    const channel = Echo.join(`message.${roomId}`);

    function add_status_code()
    {
        let status_code = 0
        usersOnline.forEach(user => {
            if(user.id == toUserId){
                status_code = 1
            }
        });
        return status_code

    }

    function check_message_status(message)
    {
        let status_t = 'unreaded'
        if(message.status == 1){
            status_t = 'readed'
        }
        return status_t
    }
    function create_message(message)
    {
        let status_t = check_message_status(message)
        console.log(status_t)
        var today = new Date();
        var time = today.toLocaleTimeString();

        if(message.id == '{{auth()->user()->id}}'){
            container.innerHTML +=`
                <div class="message text-only">
                    <div class="response">
                        <p class="text">${message.message}</p>

                    </div>
                </div>
                <p class="time my_time">${time} <i class="fa-sharp fa-solid fa-check-double ${status_t}"></i></p>
            `
        }else{
            var today = new Date();
            var time = today.toLocaleTimeString();
            container.innerHTML +=`
            <div class="message">
                <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);">
                <div class="online"></div>
                </div>
                <p class="text"> ${message.message} </p>
            </div>
            <p class="time">${time}</p>
        `;
        }
    }
    function read_all_message()
    {
        usersOnline.forEach(user => {
                if(user.id == toUserId){
                    let readedd = document.querySelectorAll('.fa-check-double.unreaded');
                    readedd.forEach(el=>{
                        el.className = 'fa-sharp fa-solid fa-check-double readed';
                    })
                    $.ajax({
                        method: "POST",
                        url: "/read_all",
                        data: {
                        toId: toUserId,
                        roomId: roomId,
                        _token: token

                        },
                    });
                }
        });
    }

    inputMessage.addEventListener('input',function(event){
        if(inputMessage.value.length == 0){
            channel.whisper('stop-typing');
        }else{

            channel.whisper('typing',{
                name: "{{$user->name}}"
            })
        }
    })
    form.addEventListener('submit',function(event){

        const userInput = inputMessage.value;
        event.preventDefault();
        let status_code = add_status_code()
            $.ajax({
                method: "POST",
                url: "/send",
                data: {
                message: userInput,
                roomid:roomId,
                touserId:toUserId,
                status:status_code,
                _token: token
                },
            });
            channel.whisper('stop-typing');
            inputMessage.value="";

    })


    channel.here((users)=>{
        usersOnline = [...users]
        console.log({usersOnline},'Here')
        usersOnline.forEach(user => {
            if(user.id == toUserId){
                status.innerHTML = "Online";
            }
        });
    })
    .joining((user) => {
        usersOnline.push(user);
        console.log({usersOnline},'Join')
        usersOnline.forEach(user => {
            if(user.id == toUserId){
                status.innerHTML = "Online";
            }
        });
    })
    .leaving((user) => {
        usersOnline = usersOnline.filter((usersOnline)=> usersOnline.id !== user.id);
        usersOnline.forEach(user => {
            if (user.id != toUserId){
                status.innerHTML = "Offline";
            }
        });
    })
    .listen('.chat-message',(event)=>{
        create_message(event)
    })
    .listenForWhisper('typing',(event)=>{
        typing.innerHTML = "typing..";
        read_all_message()
    })
    .listenForWhisper('stop-typing',(event)=>{
        typing.innerHTML = "";

    })




</script>
@endsection
