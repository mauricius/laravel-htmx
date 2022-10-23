<h1>Begin</h1>
<div>
    @fragment("upper")
        <h1>Hello from {{ $message }}</h1>
    @endfragment
</div>
<div>
    @fragment("lower")
        <p>Goodbye!</p>
    @endfragment
</div>

<h2>End</h2>