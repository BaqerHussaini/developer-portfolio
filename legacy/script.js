const portfolioData = {
  name: 'Baqer Hussaini',
  initials: 'BH',
  title: 'Full-Stack Developer',
  greeting: "Hello, I'm Baqer Hussaini",
  heroDescription:
    'Software engineer and product-minded developer crafting reliable, scalable, and user-friendly digital experiences.',
  phrases: ['digital experiences', 'modern products', 'clean systems', 'smart solutions'],
  stats: [
    { value: '4+', label: 'Years Experience' },
    { value: '18', label: 'Projects built' },
    { value: '9', label: 'Clients served' }
  ],
  aboutHeadline: 'Building thoughtful digital experiences.',
  aboutParagraphs: [
    'I am a software engineer focused on turning ideas into clear, practical products. I enjoy building interfaces, APIs, and systems that feel effortless for users and reliable for businesses.',
    'My work blends engineering discipline with design thinking, helping teams ship products that are fast, maintainable, and shaped around real user needs.'
  ],
  skills: [
    { label: 'HTML & CSS', value: 95 },
    { label: 'JavaScript', value: 92 },
    { label: 'React', value: 90 },
    { label: 'Node.js', value: 88 },
    { label: 'UI/UX', value: 93 }
  ],
  projects: [
    {
      category: 'Web App',
      title: 'TaskFlow Pro',
      description:
        'A productivity dashboard helping teams manage roles, priorities, and progress in a single workflow.',
      tags: ['React', 'Node.js', 'Dashboard']
    },
    {
      category: 'E-commerce',
      title: 'Cartora',
      description:
        'A modern online store experience designed for conversion, product storytelling, and smooth checkout flows.',
      tags: ['UI Design', 'Storefront', 'UX']
    },
    {
      category: 'Platform',
      title: 'InsightHub',
      description:
        'A reporting platform for performance analytics, dashboards, and business insights across multiple departments.',
      tags: ['Analytics', 'Data', 'SaaS']
    }
  ],
  experience: [
    {
      year: '2023 - Present',
      role: 'Full-Stack Developer',
      text: 'Designing and delivering end-to-end web applications with clean architecture, responsive interfaces, and practical business logic.'
    },
    {
      year: '2021 - 2023',
      role: 'Frontend Developer',
      text: 'Built responsive user interfaces, optimized performance, and translated product needs into polished frontend experiences.'
    },
    {
      year: '2019 - 2021',
      role: 'Web Developer',
      text: 'Created marketing sites, landing pages, and small business web products focused on speed, usability, and conversion.'
    }
  ],
  contact: {
    intro: 'Available for freelance work, product development, and collaboration opportunities.',
    email: 'engineer.baqer.hussaini@gmail.com',
    location: 'Kabul, Afghanistan',
    github: 'https://github.com',
    linkedin: 'https://linkedin.com',
    dribbble: 'https://dribbble.com'
  }
};

const elements = {
  siteLogo: document.getElementById('siteLogo'),
  heroGreeting: document.getElementById('heroGreeting'),
  heroDescription: document.getElementById('heroDescription'),
  heroRole: document.getElementById('heroRole'),
  statsList: document.getElementById('statsList'),
  projectsGrid: document.getElementById('projectsGrid'),
  experienceTimeline: document.getElementById('experienceTimeline'),
  aboutHeadline: document.getElementById('aboutHeadline'),
  aboutParagraphs: document.getElementById('aboutParagraphs'),
  skillsList: document.getElementById('skillsList'),
  contactIntro: document.getElementById('contactIntro'),
  emailLink: document.getElementById('emailLink')
};

function renderHeader() {
  if (elements.siteLogo) {
    const [firstName, lastName] = portfolioData.name.split(' ');
    elements.siteLogo.innerHTML = `${firstName[0]}<span>${lastName[0]}</span>`;
  }
}

function renderHero() {
  if (elements.heroGreeting) {
    elements.heroGreeting.textContent = portfolioData.greeting;
  }

  if (elements.heroDescription) {
    elements.heroDescription.textContent = portfolioData.heroDescription;
  }

  if (elements.heroRole) {
    elements.heroRole.textContent = portfolioData.title;
  }

  if (elements.statsList) {
    const statsHtml = portfolioData.stats
      .map(
        (stat) => `
          <li>
            <strong>${stat.value}</strong>
            <span>${stat.label}</span>
          </li>
        `
      )
      .join('');

    elements.statsList.innerHTML = statsHtml;
  }
}

function renderProjects() {
  if (!elements.projectsGrid) return;

  elements.projectsGrid.innerHTML = portfolioData.projects
    .map(
      (project, index) => `
        <article class="project-card reveal">
          <div class="project-thumb thumb-${(index % 3) + 1}">
            <span>${project.category}</span>
          </div>
          <div class="project-copy">
            <div class="project-topline">
              <h3>${project.title}</h3>
              <a href="#contact" aria-label="View ${project.title} project"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </div>
            <p>${project.description}</p>
            <ul>
              ${project.tags.map((tag) => `<li>${tag}</li>`).join('')}
            </ul>
          </div>
        </article>
      `
    )
    .join('');
}

function renderExperience() {
  if (!elements.experienceTimeline) return;

  elements.experienceTimeline.innerHTML = portfolioData.experience
    .map(
      (item) => `
        <div class="timeline-item">
          <div class="timeline-dot"></div>
          <div class="timeline-content">
            <span class="timeline-year">${item.year}</span>
            <h3>${item.role}</h3>
            <p>${item.text}</p>
          </div>
        </div>
      `
    )
    .join('');
}

function renderAbout() {
  if (elements.aboutHeadline) {
    elements.aboutHeadline.textContent = portfolioData.aboutHeadline;
  }

  if (elements.aboutParagraphs) {
    elements.aboutParagraphs.innerHTML = portfolioData.aboutParagraphs
      .map((paragraph) => `<p>${paragraph}</p>`)
      .join('');
  }

  if (elements.skillsList) {
    elements.skillsList.innerHTML = portfolioData.skills
      .map(
        (skill) => `
          <div class="skill-row">
            <span>${skill.label}</span>
            <div class="skill-bar"><i style="width: ${skill.value}%"></i></div>
          </div>
        `
      )
      .join('');
  }
}

function renderContact() {
  if (elements.contactIntro) {
    elements.contactIntro.textContent = portfolioData.contact.intro;
  }

  if (elements.emailLink) {
    elements.emailLink.href = `mailto:${portfolioData.contact.email}`;
    elements.emailLink.innerHTML = `<i class="fa-solid fa-envelope"></i> ${portfolioData.contact.email}`;
  }

  const socialLinks = document.querySelectorAll('.socials a');
  if (socialLinks.length >= 3) {
    socialLinks[0].href = portfolioData.contact.github;
    socialLinks[1].href = portfolioData.contact.linkedin;
    socialLinks[2].href = portfolioData.contact.dribbble;
  }
}

const navToggle = document.querySelector('.nav-toggle');
const mainNav = document.querySelector('.main-nav');
const navLinks = document.querySelectorAll('.main-nav a');
const yearEl = document.getElementById('year');
const revealItems = document.querySelectorAll('.reveal');
const contactForm = document.getElementById('contactForm');

if (yearEl) {
  yearEl.textContent = new Date().getFullYear();
}

renderHeader();
renderHero();
renderProjects();
renderExperience();
renderAbout();
renderContact();

if (navToggle && mainNav) {
  navToggle.addEventListener('click', () => {
    const isOpen = mainNav.classList.toggle('open');
    navToggle.setAttribute('aria-expanded', String(isOpen));
    navToggle.querySelectorAll('span').forEach((line, index) => {
      if (index === 0) {
        line.style.transform = isOpen ? 'translateY(7px) rotate(45deg)' : '';
      }
      if (index === 1) {
        line.style.opacity = isOpen ? '0' : '1';
      }
      if (index === 2) {
        line.style.transform = isOpen ? 'translateY(-7px) rotate(-45deg)' : '';
      }
    });
  });

  navLinks.forEach((link) => {
    link.addEventListener('click', () => {
      mainNav.classList.remove('open');
      navToggle.setAttribute('aria-expanded', 'false');
      navToggle.querySelectorAll('span').forEach((line) => {
        line.style.transform = '';
        line.style.opacity = '1';
      });
    });
  });
}

const setActiveLink = () => {
  const sections = document.querySelectorAll('main section[id]');
  const scrollPosition = window.scrollY + 120;

  sections.forEach((section) => {
    const top = section.offsetTop;
    const bottom = top + section.offsetHeight;
    const id = section.getAttribute('id');
    const link = document.querySelector(`.main-nav a[href="#${id}"]`);

    if (!link) return;

    const isActive = scrollPosition >= top && scrollPosition < bottom;
    link.classList.toggle('active', isActive);
  });
};

window.addEventListener('scroll', setActiveLink);
setActiveLink();

const typedText = document.getElementById('typed-text');
const phrases = portfolioData.phrases;
let phraseIndex = 0;
let charIndex = 0;
let isDeleting = false;

const typeLoop = () => {
  if (!typedText) return;

  const currentPhrase = phrases[phraseIndex];
  const displayed = currentPhrase.slice(0, charIndex);
  typedText.textContent = displayed;

  if (!isDeleting && charIndex < currentPhrase.length) {
    charIndex += 1;
    setTimeout(typeLoop, 90);
    return;
  }

  if (!isDeleting && charIndex === currentPhrase.length) {
    isDeleting = true;
    setTimeout(typeLoop, 1200);
    return;
  }

  if (isDeleting && charIndex > 0) {
    charIndex -= 1;
    setTimeout(typeLoop, 45);
    return;
  }

  isDeleting = false;
  phraseIndex = (phraseIndex + 1) % phrases.length;
  setTimeout(typeLoop, 180);
};

typeLoop();

if (contactForm) {
  contactForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const submitButton = contactForm.querySelector('button[type="submit"]');

    if (!submitButton) return;

    const originalText = submitButton.textContent;
    submitButton.textContent = 'Message Sent ✓';
    submitButton.disabled = true;
    submitButton.style.opacity = '0.9';

    setTimeout(() => {
      submitButton.textContent = originalText;
      submitButton.disabled = false;
      submitButton.style.opacity = '1';
      contactForm.reset();
    }, 1800);
  });
}

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.18 }
);

revealItems.forEach((item) => observer.observe(item));
