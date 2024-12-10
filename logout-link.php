<script>
    $(document).ready(function() {
        $('#logout-link, .logout-link').click(function(e) {
            e.preventDefault(); // Prevent the default anchor behavior
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out of your account.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Log out'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'signout.php'; // Redirect to signout.php if confirmed
                }
            });
        });
    });

//     function showLoginPopup() {
//     Swal.fire({
//         title: 'Sign in to continue',
//         html: `
//             <input type="email" id="login-email" class="swal2-input" placeholder="Email">
//             <input type="password" id="login-password" class="swal2-input" placeholder="Password">
//         `,
//         confirmButtonText: 'Sign In',
//         focusConfirm: false,
//         preConfirm: () => {
//             const email = Swal.getPopup().querySelector('#login-email').value;
//             const password = Swal.getPopup().querySelector('#login-password').value;
//             if (!email || !password) {
//                 Swal.showValidationMessage(`Please enter email and password`);
//             }
//             return { email: email, password: password };
//         }
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $.ajax({
//                 method: 'POST',
//                 url: 'api/signinAPI.php',
//                 data: { email: result.value.email, password: result.value.password }
//             })
//             .done(function(response) {
//                 if (response == 'admin') {
//                     window.location.href = 'adminPage/admin_dashboard.php';
//                 } else if (response == 'customer') {
//                     window.history.back();
//                 } else {
//                     Swal.fire({
//                         icon: 'error',
//                         title: 'Error',
//                         text: response
//                     });
//                 }
//             });
//         }
//     });
// }

</script>