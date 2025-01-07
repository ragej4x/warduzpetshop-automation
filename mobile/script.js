const form = document.getElementById('login-form');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');
const loginButton = document.getElementById('login-button');
const errorMessage = document.getElementById('error-message');

loginButton.addEventListener('click', (e) => {
  e.preventDefault();
  const username = usernameInput.value;
  const password = passwordInput.value;

  if (username === 'admin' && password === '1234') {
    localStorage.setItem('username', username);
    window.location.href = 'index.html';
  } else {
    errorMessage.innerText = 'Invalid username or password';
  }
});