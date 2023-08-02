<h1>Begin</h1>
@fragment("outer")
    <p>Outer content begin</p>
    @fragment("inner")
        <p>Inner content</p>
    @endfragment
    <p>Outer content end</p>
@endfragment

@fragment("another")
    <p>here's another</p>
@endfragment
<h2>End</h2>
