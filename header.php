<nav class="navbar navbar-expand-lg" style="background-color: var(--bs-content-bg); border-bottom: var(--bs-border-width) solid var(--bs-content-border-color);">
    <div class="container">
        <a class="navbar-brand" href="https://regist.cc"><i class="bi bi-r-square"></i> regist.cc</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-flex">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="https://regist.cc">Register a new domain</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://regist.cc/report.php">Report a domain</a>
            </li>
          </ul>
          <button class="btn btn-light ms-2" id="bd-theme" onclick="toggleDarkMode()">
            <i class="bi bi-moon-fill"></i>
          </button>
      </div>
    </div>
</nav>
<script>
  // Function to read cookie
  function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(";");
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) === " ") {
        c = c.substring(1, c.length);
      }
      if (c.indexOf(nameEQ) === 0) {
        return c.substring(nameEQ.length, c.length);
      }
    }
    return null;
  }

  // Function to set cookie
  function setCookie(name, value, days) {
    var expires = "";
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
  }

  // Set system preference if cookie (with saved preference) not present
  var systemColorMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
  if (readCookie("halfmoonColorMode") === null) {
    document.documentElement.setAttribute("data-bs-theme", systemColorMode);
  }

  // Function to toggle dark mode and set cookie
  function setTheme(theme) {
    document.documentElement.setAttribute("data-bs-theme", theme);
    monaco.editor.setTheme(theme === "dark" ? "vs-dark" : "vs-light");
  }

  function toggleDarkMode() {
    var rootPreference = document.documentElement.getAttribute("data-bs-theme");
    if (rootPreference === "light" || rootPreference === null) {
      setTheme("dark");
      setCookie("halfmoonColorMode", "dark", 365);
    } else {
      setTheme("light");
      setCookie("halfmoonColorMode", "light", 365);
    }
  }

  // Initialize initialTheme
  var initialTheme = "dark";

  // Initial theme setup
  var storedTheme = readCookie("halfmoonColorMode");
  if (storedTheme === "dark" || (storedTheme === null && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
     initialTheme = "dark";
    document.documentElement.setAttribute("data-bs-theme", "dark");
  } else {
    initialTheme = "light";
    document.documentElement.setAttribute("data-bs-theme", "light");
  }
</script>