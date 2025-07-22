// main.js

// Navbar hamburger menü toggle
function toggleMenu() {
  document.getElementById('menu').classList.toggle('active');
}

// Projeleri çekme ve gösterme
let allRepos = [];

async function fetchRepos(username) {
  try {
    const response = await fetch(`https://api.github.com/users/${username}/repos`);
    const repos = await response.json();
    allRepos = repos;
    updateLanguageFilter(repos);
    displayRepos(repos);
  } catch (err) {
    document.getElementById('repo-list').innerHTML = '<p>Projeler yüklenemedi.</p>';
  }
}

function updateLanguageFilter(repos) {
  const languages = new Set();
  repos.forEach(repo => repo.language && languages.add(repo.language));
  const filter = document.getElementById('languageFilter');
  filter.innerHTML = '<option value="all">Tümü</option>';
  languages.forEach(lang => {
    const option = document.createElement('option');
    option.value = lang;
    option.textContent = lang;
    filter.appendChild(option);
  });
}

function displayRepos(repos) {
  const list = document.getElementById('repo-list');
  list.innerHTML = '';
  repos.forEach(repo => {
    const card = document.createElement('div');
    card.className = 'project-card';

    const link = document.createElement('a');
    link.href = repo.html_url;
    link.target = '_blank';
    link.textContent = repo.name;

    const desc = document.createElement('p');
    desc.textContent = repo.description || 'Açıklama yok.';

    const meta = document.createElement('div');
    meta.className = 'project-meta';

    const starSpan = document.createElement('span');
    starSpan.innerHTML = `<svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03
      L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73
      L5.82 21z"/></svg> ${repo.stargazers_count}`;

    const forkSpan = document.createElement('span');
    forkSpan.innerHTML = `<svg viewBox="0 0 24 24"><path d="M14 2v6h-4V2h4zm-5 9a2 2 0 1 0-4 0
      2 2 0 0 0 4 0zm10 0a2 2 0 1 0-4 0 2 2 0 0 0 4 0zM7 15v7h10v-7H7z"/></svg> ${repo.forks_count}`;

    meta.appendChild(starSpan);
    meta.appendChild(forkSpan);

    card.appendChild(link);
    card.appendChild(desc);
    card.appendChild(meta);

    list.appendChild(card);
  });
}

function filterRepos() {
  const selectedLang = document.getElementById('languageFilter').value;
  if (selectedLang === 'all') {
    displayRepos(allRepos);
  } else {
    const filtered = allRepos.filter(repo => repo.language === selectedLang);
    displayRepos(filtered);
  }
}

// İletişim formu AJAX ile gönderim
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('contactForm');
  const status = document.getElementById('formStatus');

  form.addEventListener('submit', async (e) => {
    e.preventDefault(); // Sayfa yenilenmesini engelle

    status.textContent = 'Gönderiliyor...';
    status.style.color = 'black';

    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: form.method,
        body: formData,
      });

      const text = await response.text();

      if (response.ok) {
        status.style.color = 'green';
        status.textContent = text;
        form.reset();
      } else {
        status.style.color = 'red';
        status.textContent = text;
      }
    } catch (error) {
      status.style.color = 'red';
      status.textContent = 'Gönderim sırasında hata oluştu.';
    }
  });

  // CV İndir butonu
  document.getElementById('cvDownloadBtn').addEventListener('click', () => {
    const link = document.createElement('a');
    link.href = 'cv.pdf';
    link.download = 'Suleyman_Coban_CV.pdf';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });
});

// Skill bar animasyonu
function animateSkills() {
  const skillLevels = document.querySelectorAll('.skill-level');
  skillLevels.forEach(el => {
    const level = el.getAttribute('data-level');
    setTimeout(() => {
      el.style.width = level;
    }, 300);
  });
}

window.addEventListener('load', animateSkills);

// Github projelerini çek
fetchRepos("ewingdev");
