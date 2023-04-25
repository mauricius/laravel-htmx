<h1>Begin</h1>
<label>{{ $label }}</label>
<input type="text" name="email" value="{{ old('email') }}">
@error('email')
	<p>{{ $message }}</p>
@enderror
<h2>End</h2>