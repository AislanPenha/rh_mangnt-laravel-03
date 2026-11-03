<x-layout-guest pageTitle="Account confirmation">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-4">

                <!-- logo -->
                <div class="text-center mb-5">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="200px">
                </div>

                <!-- login form -->
                <div class="card p-5">

                    <form action="{{ route('confirm-submit') }}" method="post">
                        @csrf

                        <input type='hidden' name="token" value='{{ $user->confirmation_token }}'>

                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            @error('password')
                                <div class='text-danger'>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation">Repeat password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            @error('password_confirmation')
                                <div class='text-danger'>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end align-items-center">
                            <button type="submit" class="btn btn-primary px-4">Complete</button>
                        </div>

                    </form>
                    
                    @if (session('status'))
                        <div class='alert alert-success mt-3 text-center'>
                            {{ session('status') }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-layout-guest>
