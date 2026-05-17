<h1>Add Staff Page</h1>

<form action="{{ route('staff.store') }}" method="POST">
    @csrf

    <input type="text" name="name" placeholder="Enter name">
    <input type="text" name="department" placeholder="Enter department">
    <input type="text" name="schedule" placeholder="Enter schedule">

    <button type="submit">Save Staff</button>
</form>