
# Simple One to one Chat App with Laravel 

In this repo you could make a one to one private chat application




## Requirements

 - PHP - Laravel 
 - JavaScript 
 
 ## Features
  - Private one-to-one
  - User Typing..
  - User status [Online - Offline]
  - Message status [Readed - Unreaded]


## Configuration 

Go to Laravel Documentation and configurate websocket look at this :

```bash
https://laravel.com/docs/9.x/broadcasting
https://beyondco.de/docs/laravel-websockets/getting-started/installation
```
## Important Configurations 
Laravel WS configurations go to `config/websockets.php` and :
```php 
'dashboard' => [
    'port' => env('LARAVEL_WEBSOCKETS_PORT', 6001),
],
'apps' => [
    [
        'id' => env('PUSHER_APP_ID'),
        'name' => env('APP_NAME'),
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'path' => env('PUSHER_APP_PATH'),
        'capacity'=>null,
        'enable_client_messages' => true,
        'enable_statistics' => true,
    ],
],
```
Go to `config/broadcast.php` and :
```php 
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster'=> env('PUSHER_APP_CLUSTER'),
        'host' => '127.0.0.1',
        'port' => 6001,
        'scheme' => 'http',
    ],
],
```
Go to `config/app.php` and uncommend `App\Providers\BroadcastServiceProvider::class,`


After install Laravel-Echo and Pusher-js 

Client side configuration Go to `resources/js/bootstrap.js` and :
```javascript 
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: 6001,
    enabledTransports: ['ws', 'wss'],
    forceTLS:false,
    disableStats:true
});

```

## Create New Event 

```bash
php artisan make:event SendMessage
```

## SendMessage Event 

```php 
class SendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;
    public $roomId;
    public $fromId;
    public $status;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message,$user,$roomId,$fromId,$status)
    {
        $this->message = $message;
        $this->user = $user;
        $this->roomId = $roomId;
        $this->fromId = $fromId;
        $this->status = $status;


    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('message.'.$this->roomId);
    }
    public function broadcastAs()
    {
        return 'chat-message';
    }
    public function broadcastWith()
    {
        return [
            'id'=>$this->fromId,
            'name'=>$this->user,
            'message'=>$this->message,
            'status'=>$this->status,

        ];
    }


}
```


## Configurate Channel 

Go to `channels.php` from routes and Configurate your channel

we return `$user` to know the channel status 

```php 
Broadcast::channel('message.{id}', function ($user, $id) {
    return $user;
});

```
After install Laravel Websocket and Laravel Echo and Pusher From the configuration that we previously put forward


## Structure of Database   
`Chat` table migration 
| id | user_1 | user_2  | 
| ------------- | ------------- |------------- |

```php 
Schema::create('chats', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_1');
    $table->foreign('user_1')->references('id')->on('users');
    $table->unsignedBigInteger('user_2');
    $table->foreign('user_2')->references('id')->on('users');
    $table->timestamps();
});
```
`Messages` table migration 
| id | message |from_id| to_id | chat_id | is_readed | 
| ------------- | ------------- |------------- |------------- | ------------- |------------- |

```php 
Schema::create('messages', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('from_id');
    $table->foreign('from_id')->references('id')->on('users');
    $table->unsignedBigInteger('to_id');
    $table->foreign('to_id')->references('id')->on('users');
    $table->unsignedBigInteger('chat_id');
    $table->foreign('chat_id')->references('id')->on('chats');
    $table->timestamps();
});
```

## Create Models

We will create 2 Models `Chat` `Message`

First `Chat` Model
```php 
protected $fillable = [
    'user_1',
    'user_2',
];
public function user_1(){
    return $this->belongsTo(User::class,'user_1');
}
public function user_2(){
    return $this->belongsTo(User::class,'user_2');
}
public function messages(){
    return $this->hasMany(Message::class,'chat_id')->orderBy('created_at');
}
```

`Message` Model
```php 
protected $fillable = [
    'message',
    'from_id',
    'to_id',
    'chat_id',
    'is_readed'
];
public function from(){
    return $this->belongsTo(User::class,'from_id');
}
public function to(){
    return $this->belongsTo(User::class,'to_id');
}
public function chat(){
    return $this->belongsTo(Chat::class,'chat_id');
}
```
And add this to `User` Model
```php 
public function chats(){
    return $this->hasMany(Chat::class,'chat_id');
}
```
## SendMessage Controller

```php 
public function sendMessage(Request $request){
    $fromId = auth()->user()->id;
    $toUserId = $request->touserId;
    $message = $request->message;
    $status = $request->status;

    $user = auth()->user()->name;
    $id = $request->roomid;

    $save_message = Message::create([
        'message'=>$message,
        'from_id'=>$fromId,
        'to_id'=>$toUserId,
        'chat_id'=>$id,
        'is_readed'=>$status,
    ]);
    event(new SendMessage($message,$user,$id,$fromId,$status));
    return null;
}

//show room by user
public function show_room($id){
    //update auth user status to be online 
    $update = User::find(auth()->user()->id)->update([
        'is_online'=>1
    ]);
    $user = User::findOrfail($id);

    //select room if there exist , if not create new one 
    $room = Chat::where([
        ['user_1',auth()->user()->id],
        ['user_2',$id]

        ])->orWhere([
            ['user_1',$id],
            ['user_2',auth()->user()->id]
        ])->first();
    if($room == null){
        $room = Chat::create([
            'user_1'=>auth()->user()->id,
            'user_2'=>$id
        ]);
    }

    return view('pages.messanger',[
        'user'=>$user, 
        'room_id'=>$room->id,
        'messages'=>$room->messages
    ]);

    
//update message status to => is_readed 
public function read_all_messages(Request $request){
    $to_id = $request->toId;
    $room_id = $request->roomId;
    $update = Message::where([
        ['chat_id',$room_id],
        ['from_id',auth()->user()->id],
        ['to_id',$to_id],
        ['is_readed',0],
    ])->update([
        'is_readed'=>1
    ]);
    return null;
}
}
```



## Routes

```php 
//send message 
Route::post("/send",[SendMessageController::class,'sendMessage']);

//show room  
Route::get("/messanger/{id}",[SendMessageController::class,'show_room']);

//read_messages 
Route::post("/read_all",[SendMessageController::class,'read_all_messages']);
```


## Look at HTML and CSS files
We will add Blade and CSS files in repo look at it to understand JavaScript code


## Client Side 

```javascript 
    let token = $('meta[name="csrf-token"]').attr('content');
    //form-id
    let form = document.getElementById('form');
    //input-message
    let inputMessage = document.getElementById('input-message');
    //container-message
    let container = document.getElementById('container-message');
    //room-id
    let roomid = document.getElementById('room-id');
    const roomId = roomid.value;
    //other-user-id
    let touserId =document.getElementById('touserId');
    const toUserId = touserId.value;

    let send = document.getElementById('send-btn');
    let typing = document.getElementById('typing');
    let status = document.getElementById('status');
    let read_message = document.querySelectorAll('.fa-check-double.unreaded');

    //array of online users in both sides 
    let usersOnline = [];
    //create new channel and make pass room id to it 
    const channel = Echo.join(`message.${roomId}`);

    //add status code 1 => online , 0 => offline 
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

    //check message status in front view
    function check_message_status(message)
    {
        let status_t = 'unreaded'
        if(message.status == 1){
            status_t = 'readed'
        }
        return status_t
    }
    //create message
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
    //read message in typing 
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

    //show in typing 
    inputMessage.addEventListener('input',function(event){
        if(inputMessage.value.length == 0){
            channel.whisper('stop-typing');
        }else{
            channel.whisper('typing',{
                name: "{{$user->name}}"
            })
        }
    })

    //on submit 
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
```


## Errors 
You will run into some errors and I will explain some of them to you


## Typing whisper function
whisper function will not work with you so u should go to `config/websockets.php` and change enable_client_messages
to make it `true`

## Echo function is not define 
You must add `type="module"` in script tag when you use it 


