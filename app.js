'use strict';

const loginForm = document.getElementById('loginForm');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const forgotPassword = document.getElementById('forgotPassword');

togglePassword.addEventListener('click', () => {
  const passwordIsVisible = passwordInput.type === 'text';

  passwordInput.type = passwordIsVisible ? 'password' : 'text';
  togglePassword.textContent = passwordIsVisible ? 'Ver' : 'Ocultar';
  togglePassword.setAttribute(
    'aria-label',
    passwordIsVisible ? 'Mostrar senha' : 'Ocultar senha'
  );
});

loginForm.addEventListener('submit', (event) => {
  event.preventDefault();

  const email = emailInput.value.trim();
  const password = passwordInput.value.trim();

  if (!email || !password) {
    alert('Preencha seu e-mail e sua senha.');
    return;
  }

  alert('HumanIA RH — Login funcionando.');
});

forgotPassword.addEventListener('click', () => {
  alert('A recuperação de senha será conectada ao sistema de autenticação.');
});