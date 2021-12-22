<?php

session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 

require_once "config.php";
 

$username = $password = "";
$username_err = $password_err = $login_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    
    if(empty(trim($_POST["username"]))){
        $username_err = "Insira o nome de usuário.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Insira sua senha.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    
    if(empty($username_err) && empty($password_err)){
      
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
           
            $stmt->bind_param("s", $param_username);
            
            
            $param_username = $username;
            
            
            if($stmt->execute()){
              
                $stmt->store_result();
                
                
                if($stmt->num_rows == 1){                    
                    
                    $stmt->bind_result($id, $username, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                           
                            session_start();
                            
                           
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                          
                            header("location: index.php");
                        } else{
                           
                            $login_err = "Nome de usuário ou senha inválidos.";
                        }
                    }
                } else{
                    
                    $login_err = "Nome de usuário ou senha inválidos.";
                }
            } else{
                echo "Algo deu errado. Por favor, tente novamente mais tarde.";
            }

           
            $stmt->close();
        }
    }
    
   
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entrar .::. iFome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <section class="vh-100" style="background-color: #6959CD;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-2-strong" style="border-radius: 1rem;">
                <div class="card-body p-5">

                    <h3 class="text-center mb-0">Entre no iFome</h3>
                    <p class="mb-5 text-center small text-danger">e <strong>mate</strong> quem está lhe matando!</p>


                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-outline mb-4">
                            <label class="form-label" for="username">Usuário</label>
                            <input type="text" name="username" class="form-control form-control-lg <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>

                        <div class="form-outline mb-4">
                            <label class="form-label" for="password">Senha</label>
                            <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>

                        <!-- Checkbox -->
                        <div class="form-check d-flex justify-content-start mb-4 small">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                value=""
                                id="form1Example3"
                            />
                            <label class="form-check-label ms-2" for="form1Example3"> Salvar senha neste computador </label>
                        </div>

                        <div class="form-outline mb-4 text-center">
                            <button class="btn btn-danger btn-lg btn-block text-center" type="submit">Entrar no iFome</button>
                        </div>

                        <div class="divider d-flex align-items-center my-4">
                            <p class="text-center fw-bold mx-3 mb-0 text-muted">OU</p>
                        </div>

                        <p class="text-center">Não tem uma conta? <a href="register.php" class="text-decoration-none" type="submit">Criar nova conta</a>.</p>

                    </form>         

                </div>
                </div>
            </div>
            </div>
        </div>
    </section>
</body>
</html>