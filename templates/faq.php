<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>FAQ - Frequently Asked Questions</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Frequently Asked Questions (FAQ)</h2>
    <div id="accordion">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Can I have multiple registrations?
                    </button>
                </h5>
            </div>
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    The system automatically detects multiple user accounts and blocks multiple registrations from the same IP address.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        What happens if I enter the wrong address when logging in?
                    </button>
                </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    It is recommended to register only with a Zero address. Do not register with other cryptocurrency addresses, as you will not be able to withdraw your collected funds.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingThree">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Is VPN usage allowed?
                    </button>
                </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                    VPN usage is prohibited. Using a VPN can result in account suspension or termination.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingFour">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Is there any liability for damages?
                    </button>
                </h5>
            </div>
            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                <div class="card-body">
                    Since the service is free, we are not responsible for any damages that may occur. Use the service at your own risk.
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Contact</h5>
                <p><a href="mailto:youemailaddress">youemailaddress</a></p>
            </div>
            <div class="col-md-4">
                <h5>Community</h5>
                <p><a href="https://t.me/yourtelegramlink" target="_blank"><img src="https://upload.wikimedia.org/wikipedia/commons/8/82/Telegram_logo.svg" alt="Telegram" style="width: 20px; height: 20px;"> Telegram</a></p>
            </div>
            <div class="col-md-4">
                <h5>Links</h5>
                <a href="index.php">Index</a><br><a href="index.php?page=faq">FAQ</a>
            </div>
        </div>
        <p>&copy; <?php echo date('Y'); ?> <a href="./"><?php echo $faucetName; ?></a>. All Rights Reserved. Version: <?php echo $version; ?><br> Powered by <a href="https://coolscript.hu">CoolScript</a></p>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
