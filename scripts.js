document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function (event) {
            // Validate form
            const inputs = form.querySelectorAll('input, textarea, select');
            let valid = true;

            inputs.forEach(input => {
                if (input.hasAttribute('required') && input.value.trim() === '') {
                    valid = false;
                    input.classList.add('error');
                } else {
                    input.classList.remove('error');
                }
            });

            if (!valid) {
                event.preventDefault();
                alert('Please fill out all required fields.');
            }
        });
    });

    // Example for toggling visibility of an element
    const toggleButton = document.getElementById('toggle-button');
    const toggleElement = document.getElementById('toggle-element');

    if (toggleButton && toggleElement) {
        toggleButton.addEventListener('click', function () {
            if (toggleElement.style.display === 'none') {
                toggleElement.style.display = 'block';
            } else {
                toggleElement.style.display = 'none';
            }
        });
    }

    
    // Handle payment form submission dynamically
    const paymentForm = document.getElementById('payment-form');
    const successMessage = document.getElementById('success-message');
    
        if (paymentForm && successMessage) {
            paymentForm.addEventListener('submit', function (event) {
                event.preventDefault();
                
                const formData = new FormData(paymentForm);
                
                fetch('process_payment.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.includes('Payment successful!')) {
                        successMessage.style.display = 'block';
                        paymentForm.reset();
                    } else {
                        alert(data);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    
    
});

document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login-form');

    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (email.trim() === '' || password.trim() === '') {
                event.preventDefault();
                alert('Please fill out all required fields.');
            }
        });
    }
});
