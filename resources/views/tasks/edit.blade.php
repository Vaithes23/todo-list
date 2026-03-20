<h2>Edit Task</h2>

<form action="/task/update/{{$task->id}}" method="POST">
@csrf

<input type="text" name="title" value="{{$task->title}}">

<button type="submit">Update</button>

</form>