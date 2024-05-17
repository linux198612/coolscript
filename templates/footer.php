
</div>
<footer class="footer text-center">
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> <a href="./"><?php echo $faucetName; ?></a>. All Rights Reserved. Version: <?php echo $version; ?><br> Powered by <a href="https://coolscript.hu">CoolScript</a></p>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Az aktív menüpont beállítása az oldal betöltésekor
        $(document).ready(function(){
            var url = window.location;
            $('ul.navbar-nav a[href="'+ url +'"]').parent().addClass('active');
            $('ul.navbar-nav a').filter(function() {
                return this.href == url;
            }).parent().addClass('active');
        });
    </script>
</body>
</html>
