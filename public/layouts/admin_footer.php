
        </main>
        <footer>
            Copyright <?php date("Y", time()); ?> Natac
        </footer>
    <script src="../javascript/delete.js"></script>

    </body>
</html>
<?php
if(isset($db)) {
    $db->close();
}
?>