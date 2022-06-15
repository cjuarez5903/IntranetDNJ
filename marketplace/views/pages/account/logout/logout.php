<?php 

session_destroy();

echo '<script>

localStorage.removeItem("token_user");

window.location = "'.$path.'account&login";

</script>';

