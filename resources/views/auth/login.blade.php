<!DOCTYPE html>
<html lang="tr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Giriş | Ionizevel CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Segoe UI", "lucida grande", tahoma, verdana, arial, sans-serif;
            font-size: 1em;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        
        .login-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 40px;
            backdrop-filter: blur(10px);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            letter-spacing: -0.5px;
        }
        
        .logo h1 span {
            color: #667eea;
        }
        
        .version {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 5px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }
        
        .form-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group input::placeholder {
            color: #9ca3af;
        }
        
        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .submit-btn {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(102, 126, 234, 0.5);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #94a3b8;
            font-size: 12px;
        }
        
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        
        @media (max-width: 480px) {
            .login-box {
                padding: 30px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <h1>Ionize<span>vel</span></h1>
                <div class="version">Laravel CMS v1.0.0</div>
            </div>
            
            @if ($errors->any())
                <div class="error-message">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="username">Kullanıcı Adı</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="{{ old('username') }}"
                        placeholder="Kullanıcı adınızı girin"
                        required 
                        autofocus
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Şifrenizi girin"
                        required
                    >
                </div>
                
                <button type="submit" class="submit-btn">Giriş Yap</button>
            </form>
            
            <div class="footer">
                Powered by <a href="#">Ionizevel CMS</a>
            </div>
        </div>
    </div>
</body>
</html>
