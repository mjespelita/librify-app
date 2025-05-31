let buttonInstall = document.getElementById('btn');
let defferedPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    defferedPrompt = e;
    console.log('beforenstallprompt was fired');
})

buttonInstall.addEventListener('click', async () => {
    defferedPrompt.prompt();
    const { outcome } = await defferedPrompt.userChoice;
    console.log(`Use response to the install prompt: ${outcome}`);
    defferedPrompt = null;
})

function isPWAInstalled() {
  const isStandalone = window.matchMedia('(display-mode: standalone)').matches;
  const isIOSStandalone = window.navigator.standalone === true;
  return isStandalone || isIOSStandalone;
}

document.addEventListener("DOMContentLoaded", () => {
  const installSection = document.getElementById("install-buttons");
  const installedMessage = document.getElementById("already-installed");

  if (isPWAInstalled()) {
    // Hide install options
    installSection.style.display = "none";
    installedMessage.style.display = "block";
    console.log('installed')
  } else {
    // Show install prompt
    installSection.style.display = "block";
    installedMessage.style.display = "none";
    console.log('not installed')
  }
});
