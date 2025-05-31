
<div style='text-align: right;'>
    @if (session()->has('success'))
    <p class='alert alert-success text-success mb-0'><i class='fas fa-check'></i> {{ session()->get('success') }}</p>
    @endif

    @if (session()->has('error'))
        <p class='alert alert-danger text-danger mb-0'><i class='fas fa-warning'></i> {{ session()->get('error') }}</p>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $err)
            <p class='alert alert-danger text-danger mb-0'><i class='fas fa-warning'></i> {{ $err }}</p>
        @endforeach
    @endif
</div>
