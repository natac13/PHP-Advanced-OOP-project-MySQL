        </main>
        <footer>
            Copyright <?php date("Y", time()); ?> Natac
        </footer>
    </body>
</html>
<?php
if(isset($db)) {
    $db->close();
}
?>