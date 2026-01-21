<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>QAMS - Login - Riphah International University</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="shortcut icon" href="{{ asset('public/assets/images/header.jpeg') }}"/>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
<link href="{{ asset('public/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
body {
  /* background: linear-gradient(135deg,#475c88,#0a1325); */
  font-family: "Segoe UI", system-ui, sans-serif;
  margin: 0;
  padding: 0;
  height: 100vh;
  overflow: hidden;
}

.login-wrapper {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
  max-width: 900px;
}

.login-card {
  display: flex;
  border-radius: 1.5rem;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 40px 90px rgba(0,0,0,.5);
}

.cover {
  width: 50%;
  background: linear-gradient(135deg,#193869,#193869);
  /* background: #193869; */
  display: flex;
  align-items: center;
  justify-content: center;
  /* padding: 3.5rem 4rem; */
  /* padding: 4.5rem; */
}

.cover-content {
  text-align: left;
}

.cover img {
  width: 170px;
  max-width: 100%;
  background: #fff;
  padding: 14px;
  border-radius: 16px;
  box-shadow: 0 18px 40px rgba(0,0,0,.35);
  margin-bottom: 1.5rem;
}

.cover h2,.cover p {
  color: #fff;
}

.cover h2 {
  font-weight: 600;
  letter-spacing: 0.4px;
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.cover p {
  font-size: 1rem;
  opacity: 0.9;
}

.cover-footer {
  margin-top: 2rem;
  font-size: 0.7rem;
  opacity: 0.7;
  color: #fff;
}

.form-side {
  width: 60%;
  padding: 3.5rem 4rem;
}

.input-group-text {
  cursor: pointer;
}

.btn-primary {
  background: linear-gradient(135deg,#193869,#193869);
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-primary i {
  margin-right: 8px;
}

#loginButton {
    transition: transform 0.1s ease, box-shadow 0.1s ease;
}

#loginButton:hover {
    transform: translateY(-1px);
}

#loginButton:active {
    transform: translateY(0);
}

.forgot-link {
  text-decoration: none;
  transition: all 0.2s ease;
}

.forgot-link:hover {
  text-decoration: underline;
}

body.dark-mode {
  background: #020617;
}

body.dark-mode .login-card {
  background: #020617;
  color: #e5e7eb;
}

body.dark-mode .form-control {
  background: #020617;
  border-color: #334155;
  color: #e5e7eb;
}

@media (max-width: 992px) {
  .cover {
    padding: 1.5rem;
  }
  .cover h2 {
    font-size: 1.8rem;
  }
  .cover p {
    font-size: 0.95rem;
  }
}

@media (max-width: 768px) {
  .login-card {
    flex-direction: column;
    height: auto;
  }

  .cover {
    width: 100%;
    order: -1;
    padding: 1.5rem;
  }

  .cover img {
    max-width: 120px;
    margin-bottom: 1rem;
  }

  .cover h2 {
    font-size: 1.5rem;
  }

  .cover p {
    font-size: 0.85rem;
  }

  .form-side {
    width: 100%;
    padding: 2rem 1rem;
  }
}
</style>
</head>
<body>
<div class="login-wrapper">
  <div class="login-card">
    <div class="cover">
      <div class="cover-content">
        <div class="cover-top">
          <img src="{{ asset('public/assets/images/riphah-email.png') }}" alt="Logo">
          <h2>Welcome</h2>
          <p>Quality Audit Management System</p>
        </div>
        <div class="cover-footer">
          <div>2026 © QAMS - Riphah International University</div>
          <div class="mx-1">Designed and Developed by MIS Department</div>
        </div>
      </div>
    </div>
    <div class="form-side">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold mb-0">Sign In</h4>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="themeToggle"></button>
      </div>
      @if ($errors->any())
        <div class="alert alert-danger small alert-dismissible fade show" role="alert">
          @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      <form method="POST" action="{{ route('userlogin') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter your email" required autofocus>
        </div>
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-2">
            <label class="form-label mb-0">Password</label>
            <a href="#" class="text-danger small forgot-link">Forgot password?</a>
          </div>
          <div class="input-group">
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            <span class="input-group-text" id="togglePassword">
              <i class="ki-duotone ki-eye">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
              </i>
            </span>
          </div>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="remember" id="remember">
          <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <button type="submit" id="loginButton" class="btn btn-primary w-100 fw-semibold">
          <i class="ki-duotone ki-entrance-left"><span class="path1"></span><span class="path2"></span></i>
          <span class="btn-text">Login</span>
          <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true" id="loginSpinner"></span>
        </button>
      </form>
    </div>
  </div>
</div>
<script src="{{ asset('public/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('public/assets/js/custom/authentication/sign-in/general.js') }}"></script>
<script>
  const passwordInput = document.getElementById('password');
  const toggle = document.getElementById('togglePassword');
  const icon = toggle.querySelector('i');
  toggle.addEventListener('click', () => {
    if(passwordInput.type === 'password'){
      passwordInput.type = 'text';
      icon.classList.replace('ki-eye', 'ki-eye-slash');
    } else {
      passwordInput.type = 'password';
      icon.classList.replace('ki-eye-slash', 'ki-eye');
    }
  });

  const body = document.body;
  const themeToggle = document.getElementById('themeToggle');
  if(localStorage.getItem('theme') === 'dark') body.classList.add('dark-mode');

  function updateThemeIcon(){
    themeToggle.innerHTML = body.classList.contains('dark-mode')
      ? `<i class="ki-duotone ki-sun"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span></i>`
      : `<i class="ki-duotone ki-moon"><span class="path1"></span><span class="path2"></span></i>`;
  }
  updateThemeIcon();
  themeToggle.addEventListener('click', ()=>{
    body.classList.toggle('dark-mode');
    localStorage.setItem('theme', body.classList.contains('dark-mode')?'dark':'light');
    updateThemeIcon();
  });

  const loginForm = document.querySelector('form');
  const loginButton = document.getElementById('loginButton');
  const loginSpinner = document.getElementById('loginSpinner');
  const btnText = loginButton.querySelector('.btn-text');

  loginForm.addEventListener('submit', function(){
    if(loginForm.checkValidity()){
      loginSpinner.classList.remove('d-none');
      btnText.textContent = 'Logging in...';
      loginButton.disabled = true;
    }
  });
</script>
</body>
</html>
