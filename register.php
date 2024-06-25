<?php
session_start();
// Steps to follow:
// 1. Connect to the database
// 2. Check if the form is submitted
// 3. Handle any errors that occur
// 4. Sanitize the input data
// 5. Hash the password
// 6. Prepare the SQL statement
// 7. Execute the SQL statement
// 8. Redirect the user to the login page

// Connect to the database
require 'helpers.php';
require 'db.php';

class Register{
    protected $helpers;
    protected $db;
    // Error Bag
    protected $errors;
    //Input Data
    public $name;
    public $email;
    protected $password;

    public function __construct($db,$helpers,$errors,$password){
        $this->helpers  = $helpers;
        $this->db       = $db;
        $this->errors   = $errors;
        $this->password = $password;
    }

    public function getDBConn(){
        $this->db->getConnection();
    }

    public function getErrors(){
        return $this->errors;
    }

    public function register(){
        // Input Data
        $this->name = '';
        $this->email = '';
        $this->password = '';
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle Any Errors That Occur
            // Sanitize and Validate the Name Field
            if (empty($_POST['name'])) {
                $this->errors['name'] = 'Please provide a name';
            } else {
                $this->name = $this->helpers->sanitize($_POST['name']);
            }
            // Sanitize and Validate the Email Field
            if (empty($_POST['email'])) {
                $this->errors['email'] = 'Please provide an email address';
            } else {
                $this->email = $this->helpers->sanitize($_POST['email']);
                if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                    $this->errors['email'] = 'Please provide a valid email address';
                }
            }
            // Sanitize and Validate the Password Field
            if (empty($_POST['password'])) {
                $this->errors['password'] = 'Please provide a password';
            } elseif (strlen($_POST['password']) < 8) {
                $this->errors['password'] = 'Password must be at least 8 characters';
            } else {
                $this->password = $this->helpers->sanitize($_POST['password']);
                // Hash The Password
                $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            }
            if (empty($this->errors)) {
                // Prepare The SQL Statement
                $query  = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
                $stmt   = $this->db->getPDO()->prepare($query);
                // Execute The SQL Statement
                $params = [
                    ':name' => $this->name,
                    ':email' => $this->email,
                    ':password' => $this->password
                ];
                if ($stmt->execute($params)) {
                    $this->helpers->flash('success', 'You have successfully registered. Please log in to continue');
                    header('Location: login.php');
                    exit;
                } else {
                    $this->errors['auth_error'] = 'An error occurred. Please try again';
                }
            }
        }
    }
}
$register = new Register(new DB(new PDO("mysql:host=localhost;dbname=veronicadb;port=3306","sk123","Sk123&*("),"mysql:host=localhost;dbname=veronicadb;port=3306","sk123","Sk123&*("), new Helpers(),[],"");
$register->getDBConn();
$register->register();
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Inter Google Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

        <!-- Tailwindcss CDN -->
        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji']
                        }
                    }
                }
            }
        </script>

        <title>Get Started | Veronica</title>
    </head>
    <body class="bg-gray-100 flex h-full items-center py-16">
        <div class="w-full max-w-md mx-auto p-6">
            <?php if (isset($register->errors['auth_error'])) : ?>
                <div class="mt-2 bg-red-100 border border-red-200 text-sm text-red-800 rounded-lg p-4" role="alert">
                    <span class="font-bold"><?= $register->errors['auth_error']; ?></span>
                </div>
            <?php endif; ?>
            <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="p-4 sm:p-7">
                    <div class="text-center">
                        <h1 class="block text-2xl font-bold text-gray-800">Sign up</h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Already have an account?
                            <a class="text-blue-600 decoration-2 hover:underline font-medium" href="login.php">
                                Log in here
                            </a>
                        </p>
                    </div>
                    <div class="mt-5">
                        <!-- Form -->
                        <form action="register.php" method="POST" novalidate>
                            <div class="grid gap-y-4">
                                <!-- Form Group -->
                                <div>
                                    <label for="name" class="block text-sm mb-2">Full Name</label>
                                    <div class="relative">
                                        <input type="text" id="name" name="name" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" required aria-describedby="name-error" placeholder="Sultan Muhammad Alp Arslan" value="<?= $register->name; ?>">
                                        <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                            <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <?php if (isset($register->errors['name'])) : ?>
                                        <p class="text-xs text-red-600 mt-2" id="name-error"><?= $register->errors['name']; ?></p>
                                    <?php endif; ?>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div>
                                    <label for="email" class="block text-sm mb-2">Email address</label>
                                    <div class="relative">
                                        <input type="email" id="email" name="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" required aria-describedby="email-error" placeholder="example@mail.com" value="<?= $register->email; ?>">
                                        <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                            <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <?php if (isset($register->errors['email'])) : ?>
                                        <p class="text-xs text-red-600 mt-2" id="email-error"><?= $register->errors['email']; ?></p>
                                    <?php endif; ?>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div>
                                    <label for="password" class="block text-sm mb-2">Password</label>
                                    <div class="relative">
                                        <input type="password" id="password" name="password" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" required aria-describedby="password-error" placeholder="••••••••">
                                        <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                                            <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <?php if (isset($register->errors['password'])) : ?>
                                        <p class="text-xs text-red-600 mt-2" id="password-error"><?= $register->errors['password']; ?></p>
                                    <?php endif; ?>
                                </div>
                                <!-- End Form Group -->

                                <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">Sign
                                    up</button>
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>