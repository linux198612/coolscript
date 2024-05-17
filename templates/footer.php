<footer class="footer text-center">
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> <a href="./"><?php echo $faucetName; ?></a>. All Rights Reserved. Version: <?php echo $version; ?><br> Powered by <a href="https://coolscript.hu">CoolScript</a></p>
    </div>
</footer>
</section>
            </article>
        </article>
    </main>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(function () {
            var sidebar = $('.sidebar')
            sidebar.userSet = false

            $('.sidebar-toggle').on('click', function () {
                sidebar.toggleClass('hidden');
                sidebar.userSet = true
            });

            $(window).on('resize', function () {
                if (!sidebar.userSet) {
                    if (document.body.clientWidth >= 768) {
                        sidebar.removeClass('hidden');
                    } else {
                        sidebar.addClass('hidden');
                    }
                }
            })
        })
    </script>
</body>
</html>
