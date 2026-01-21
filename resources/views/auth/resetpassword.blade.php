<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('public/assets/images/favicon.ico')}}">
    <!-- Theme Config Js -->
    <script src="{{asset('public/assets/js/hyper-config.js')}}"></script>
    <!-- App css -->
    <link href="{{asset('public/assets/css/app-saas.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
    <!-- Icons css -->
    <link href="{{asset('public/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />

    <title>Forgot Password? | Admin Dashboard</title>
</head>
<body class="authentication-bg pb-0">
    <div class="auth-fluid">
        <!--Auth fluid left content -->
        <div class="auth-fluid-form-box">
            <div class="card-body d-flex flex-column h-100 gap-3">
                <div class="auth-brand text-center text-lg-start">
                    <a class="logo-dark" style="background: #60746c;">
                        @php
                          use App\Models\OrganizationLogo;
                          $organizationLogo = OrganizationLogo::first();
                        @endphp
                        
                        @if($organizationLogo && $organizationLogo->org_logo)
                          <img src="{{ asset('/public/organizationlogo/' . $organizationLogo->org_logo) }}" alt="dark logo" height="80"/>
                        @else
                          <img src="{{ asset('public/organizationlogo/21.png') }}" alt="dark logo" height="80"/>
                        @endif
                      </span>  
                    </a>
                </div>
                <div class="my-auto">
                    <!-- title-->
                    <h4 class="mt-0">Reset Password</h4>
                    <p class="text-muted mb-4">Enter your email address and we'll send you an email with instructions to reset your password.</p>
                    <!-- form -->
                    <form method="POST" action="{{ route('changepassword') }}">
                      @csrf                        
                        <div class="mb-3">
                            <label class="form-label">Change Password</label>
							<div class="input-group input-group-merge">
								<input class="form-control" type="password" name="new_password" value="{{old('new_password')}}" required="" placeholder="New Password">
                                <div class="input-group-text" id="togglePassword" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid mb-0 text-center">
							<input type="hidden" value="{{$unique_id}}" name="id">
							<button class="btn btn-primary" type="submit">
								<i class="mdi mdi-lock-reset"></i>Reset Password
							</button>
                        </div>
                        {{-- <div class="text-center mt-4">
                            <p class="text-muted font-16">Sign in with</p>
                            <ul class="social-list list-inline mt-3">
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i class="mdi mdi-facebook"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i class="mdi mdi-google"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-info text-info"><i class="mdi mdi-twitter"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i class="mdi mdi-github"></i></a>
                                </li>
                            </ul>
                        </div> --}}
                    </form>
                </div>
				<footer class="footer footer-alt">
					<p class="text-muted">Back to <a href="{{route('indexlogin')}}" class="text-muted ms-1"><b>Log In</b></a></p>
				  </footer>
            </div>
        </div>
        <div class="auth-fluid-right text-center">
            <div class="auth-user-testimonial">
                <h2 class="mb-3">Welcome to!</h2>
                <p>Riphah</p>
                <p class="lead"><i class="mdi mdi-format-quote-open"></i> It's a elegent templete! <i class="mdi mdi-format-quote-close"></i>
                </p>
                <p>Designed and Developed by the MIS Department</p>
            </div>
        </div>
    </div>
    <!-- Vendor js -->
    <script src="{{asset('public/assets/js/vendor.min.js')}}"></script>
    <!-- App js -->
    <script src="{{asset('public/assets/js/app.min.js')}}"></script>

</body>
</html>