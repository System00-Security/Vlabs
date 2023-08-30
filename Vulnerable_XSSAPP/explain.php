<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cross-Site Scripting (XSS) - Explained</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Cross-Site Scripting (XSS) - Explained</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
            </ul>
    </div>
    
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <h2>What is Cross-Site Scripting (XSS)?</h2>
            <p>
                Cross-Site Scripting (XSS) is a type of security vulnerability that allows attackers to inject malicious
                scripts into web pages viewed by other users. This occurs when a web application doesn't properly
                validate or sanitize user inputs and outputs them directly onto the page.
            </p>
            <p>
                The injected scripts execute in the context of the victim's browser, enabling attackers to steal sensitive
                information, hijack user sessions, or perform other malicious actions on behalf of the user.
            </p>
            <h3>Types of XSS:</h3>
            <ul>
                <li><strong>Reflected XSS:</strong> Occurs when the injected script is reflected off a web server, usually
                    through a URL parameter or form input. It requires the victim to click on a malicious link to trigger
                    the attack.</li>
                <li><strong>Stored XSS:</strong> Occurs when the injected script is permanently stored on the server, and
                    every user who accesses the vulnerable page is affected. It is more dangerous than reflected XSS as
                    victims don't need to interact with the attacker's URL.</li>
                <li><strong>DOM-based XSS:</strong> Occurs when the client-side scripts modify the Document Object Model
                    (DOM) of a web page, leading to the execution of malicious code.</li>
            </ul>
        </div>
        <div class="col-md-6">
            <ul class="nav nav-tabs" id="codeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="php-tab" data-bs-toggle="tab" href="#php" role="tab">PHP</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="asp-tab" data-bs-toggle="tab" href="#asp" role="tab">ASP</a>
                </li>
            </ul>
            <div class="tab-content mt-3" id="codeTabContent">
                <div class="tab-pane fade show active" id="php" role="tabpanel">
                    <div class="alert alert-danger">
                        <h4>Example of Reflected XSS (PHP):</h4>
                        <p>Let's assume this vulnerable PHP code reflects the "name" parameter in the page:</p>
                        <code>
                            &lt;?php<br>
                            if (isset($_GET['name'])) {<br>
                            &nbsp;&nbsp;$name = $_GET['name'];<br>
                            &nbsp;&nbsp;echo "&lt;p&gt;Welcome, " . $name . "!&lt;/p&gt;";<br>
                            }<br>
                            ?&gt;
                        </code>
                        <p>When a user visits the URL with a malicious name parameter, like<br>
                            <code>https://example.com/vulnerable.php?name=&lt;script&gt;alert('XSS Attack!')&lt;/script&gt;</code><br>
                            the script gets executed, and an alert will show up saying "XSS Attack!"</p>
                    </div>
                    <div class="alert alert-danger mt-4">
                        <h4>Example of Stored XSS (PHP):</h4>
                        <p>Suppose this vulnerable PHP code stores comments without proper validation:</p>
                        <code>
                            &lt;?php<br>
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' &amp;&amp; isset($_POST['comment'])) {<br>
                            &nbsp;&nbsp;$comment = $_POST['comment'];<br>
                            &nbsp;&nbsp;// Store the comment in a file (vulnerable storage)<br>
                            &nbsp;&nbsp;file_put_contents('comments.txt', $comment . "\n", FILE_APPEND);<br>
                            }<br>
                            ?&gt;
                        </code>
                        <p>When an attacker submits a comment containing a malicious script, like<br>
                            <code>&lt;script&gt;malicious_code_here();&lt;/script&gt;</code><br>
                            it will be stored in the file. When other users view the comments, the script will execute for all of them.</p>
                    </div>
                    <div class="alert alert-danger mt-4">
                        <h4>Example of DOM-based XSS (PHP):</h4>
                        <p>In this HTML page, the vulnerable JavaScript code directly retrieves the "name" parameter from the URL and sets it as the text content of an element:</p>
                        <code>
                            &lt;h1 id="greeting"&gt;&lt;/h1&gt;<br>
                            &lt;script&gt;<br>
                            &nbsp;&nbsp;// Vulnerable JavaScript code for DOM-based XSS<br>
                            &nbsp;&nbsp;var urlParams = new URLSearchParams(window.location.search);<br>
                            &nbsp;&nbsp;var name = urlParams.get('name');<br>
                            &nbsp;&nbsp;document.getElementById('greeting').innerText = 'Welcome, ' + name + '!';<br>
                            &lt;/script&gt;
                        </code>
                        <p>An attacker can craft a URL with a malicious name parameter, like<br>
                            <code>https://example.com/vulnerable.html?name=&lt;script&gt;alert('XSS Attack!')&lt;/script&gt;</code><br>
                            which will be executed as JavaScript on the page.</p>
                    </div>
                </div>
                <div class="tab-pane fade" id="asp" role="tabpanel">
                    <div class="alert alert-danger">
                        <h4>Example of Reflected XSS (ASP):</h4>
                        <p>Suppose this vulnerable ASP code reflects the "name" parameter in the page:</p>
                        <code>
                            &lt;%<br>
                            Dim name<br>
                            name = Request.QueryString("name")<br>
                            Response.Write("&lt;p&gt;Welcome, " &amp; name &amp; "!&lt;/p&gt;")<br>
                            %&gt;
                        </code>
                        <p>When a user visits the URL with a malicious name parameter, like<br>
                            <code>https://example.com/vulnerable.asp?name=&lt;script&gt;alert('XSS Attack!')&lt;/script&gt;</code><br>
                            the script gets executed, and an alert will show up saying "XSS Attack!"</p>
                    </div>
                    <div class="alert alert-danger mt-4">
                        <h4>Example of Stored XSS (ASP):</h4>
                        <p>Suppose this vulnerable ASP code stores comments without proper validation:</p>
                        <code>
                            &lt;%<br>
                            If Request.ServerVariables("REQUEST_METHOD") = "POST" Then<br>
                            &nbsp;&nbsp;Dim comment<br>
                            &nbsp;&nbsp;comment = Request.Form("comment")<br>
                            &nbsp;&nbsp;Set fso = CreateObject("Scripting.FileSystemObject")<br>
                            &nbsp;&nbsp;Set file = fso.OpenTextFile("comments.txt", 8, True)<br>
                            &nbsp;&nbsp;file.WriteLine(comment)<br>
                            &nbsp;&nbsp;file.Close<br>
                            &nbsp;&nbsp;Set file = Nothing<br>
                            &nbsp;&nbsp;Set fso = Nothing<br>
                            End If<br>
                            %&gt;
                        </code>
                        <p>When an attacker submits a comment containing a malicious script, like<br>
                            <code>&lt;script&gt;malicious_code_here();&lt;/script&gt;</code><br>
                            it will be stored in the "comments.txt" file. When other users view the comments, the script will execute for all of them.</p>
                    </div>
                    <div class="alert alert-danger mt-4">
                        <h4>Example of DOM-based XSS (ASP):</h4>
                        <p>In this HTML page, the vulnerable JavaScript code directly retrieves the "name" parameter from the URL and sets it as the text content of an element:</p>
                        <code>
                            &lt;h1 id="greeting"&gt;&lt;/h1&gt;<br>
                            &lt;script&gt;<br>
                            &nbsp;&nbsp;' Vulnerable JavaScript code for DOM-based XSS<br>
                            &nbsp;&nbsp;Function GetURLParam(name)<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;Dim value<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;value = Request.QueryString(name)<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;GetURLParam = value<br>
                            &nbsp;&nbsp;End Function<br>
                            &nbsp;&nbsp;name = GetURLParam("name")<br>
                            &nbsp;&nbsp;document.getElementById("greeting").innerText = "Welcome, " &amp; name &amp; "!";<br>
                            &lt;/script&gt;
                        </code>
                        <p>An attacker can craft a URL with a malicious name parameter, like<br>
                            <code>https://example.com/vulnerable.asp?name=&lt;script&gt;alert('XSS Attack!')&lt;/script&gt;</code><br>
                            which will be executed as JavaScript on the page.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="mt-4 bg-dark text-center text-white py-2">
    &copy; <?php echo date('Y'); ?> System00 Security Bangladesh
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
