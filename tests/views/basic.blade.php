<h1>Begin</h1>
@fragment("double")
    <p>Hello from {{ $message }}</p>
@endfragment

@fragment('single')
    <p>Howdy from {{ $message }}</p>
@endfragment
<h2>End</h2>
