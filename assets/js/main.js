// ==========================================================================
// BIG SCREEN CINEMAS - CONSOLIDATED JAVASCRIPT ENGINE
// ==========================================================================

// 1. Navigation Active State & Mobile Menu
function initActiveNavigation() {
  const currentPath = window.location.pathname.split('/').pop() || 'index.html';
  const navLinks = document.querySelectorAll('.ifs-cinema-nav-link');

  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (!href) return;
    
    const linkPath = href.split('/').pop();
    if (linkPath === currentPath || (currentPath === '' && linkPath === 'index.html')) {
      link.classList.add('ifs-cinema-active');
    } else {
      link.classList.remove('ifs-cinema-active');
    }
  });

  const mobileToggle = document.querySelector('.ifs-cinema-mobile-toggle');
  const navMenu = document.querySelector('.ifs-cinema-nav-menu');
  if (mobileToggle && navMenu) {
    mobileToggle.addEventListener('click', () => {
      navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex';
      navMenu.style.flexDirection = 'column';
    });
  }
}

// 2. Dashboard Tab Switcher
function switchDashboardTab(tabName, btnEl) {
  document.querySelectorAll('.ifs-cinema-tab-nav-btn').forEach(b => b.classList.remove('active'));
  btnEl.classList.add('active');

  document.querySelectorAll('.ifs-cinema-tab-pane').forEach(p => p.classList.remove('active'));
  const targetPane = document.getElementById(`pane-${tabName}`);
  if (targetPane) {
    targetPane.classList.add('active');
  }
}

// 3. Checkout Payment Gateway Handler
let activeMethod = 'bKash';

function selectPaymentTile(tileEl, method) {
  document.querySelectorAll('.ifs-cinema-payment-tile').forEach(t => t.classList.remove('active'));
  tileEl.classList.add('active');
  activeMethod = method;

  const titleEl = document.getElementById('paymentGatewayPrompt');
  const descEl = document.getElementById('paymentGatewayDesc');
  const fieldEl = document.getElementById('accountNoField');

  if (!titleEl || !descEl || !fieldEl) return;

  if (method === 'bKash') {
    titleEl.innerText = 'bKash Instant Gateway';
    descEl.innerText = 'You will be redirected to the secure bKash PGW to verify your wallet and complete payment instantly.';
    fieldEl.innerHTML = `<label>bKash Account Number</label><input type="text" class="ifs-cinema-input" placeholder="017XXXXXXXX">`;
  } else if (method === 'Nagad') {
    titleEl.innerText = 'Nagad Direct Pay';
    descEl.innerText = 'Enter your registered Nagad number to receive a secure OTP verification prompt.';
    fieldEl.innerHTML = `<label>Nagad Account Number</label><input type="text" class="ifs-cinema-input" placeholder="018XXXXXXXX">`;
  } else {
    titleEl.innerText = 'Debit / Credit Card (SSLCommerz)';
    descEl.innerText = 'All major cards (Visa, MasterCard, Amex) supported via 3D secure authentication.';
    fieldEl.innerHTML = `
      <div class="ifs-cinema-form-grid-2">
        <div><label>Card Number</label><input type="text" class="ifs-cinema-input" placeholder="4111 2222 3333 4444"></div>
        <div><label>Expiry Date</label><input type="text" class="ifs-cinema-input" placeholder="MM/YY"></div>
      </div>
    `;
  }
}

function processFinalPayment() {
  const nameEl = document.getElementById('guestName');
  const phoneEl = document.getElementById('guestPhone');
  
  const name = nameEl ? nameEl.value.trim() : 'Guest Customer';
  const phone = phoneEl ? phoneEl.value.trim() : '';

  if (!phone) {
    alert('Please provide your phone number for SMS ticket confirmation.');
    return;
  }

  const token = '#BSC-' + Math.floor(1000 + Math.random() * 9000) + '-TX';
  const holderEl = document.getElementById('passHolderName');
  const methodEl = document.getElementById('passPaymentMethod');
  const tokenEl = document.getElementById('passTokenCode');

  if (holderEl) holderEl.innerText = name;
  if (methodEl) methodEl.innerText = `${activeMethod} • BDT 1,050.00`;
  if (tokenEl) tokenEl.innerText = `TOKEN: ${token}`;

  const modalEl = document.getElementById('checkoutPassModal');
  if (modalEl) modalEl.classList.add('active');
}

function closePassModal() {
  const modalEl = document.getElementById('checkoutPassModal');
  if (modalEl) modalEl.classList.remove('active');
}

// 4. Gallery Lightbox & Filter Engine
function filterGallery(category, btnEl) {
  document.querySelectorAll('.ifs-cinema-filter-btn').forEach(b => b.classList.remove('active'));
  btnEl.classList.add('active');

  const cards = document.querySelectorAll('.ifs-cinema-gallery-card, .ifs-cinema-gallery-card-pro');
  cards.forEach(card => {
    if (category === 'all' || card.dataset.category === category) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
}

function openLightbox(imgUrl, title, desc) {
  const img = document.getElementById('lightboxImg');
  const t = document.getElementById('lightboxTitle');
  const d = document.getElementById('lightboxDesc');
  const modal = document.getElementById('lightboxModal');

  if (img) img.src = imgUrl;
  if (t) t.innerText = title;
  if (d) d.innerText = desc;
  if (modal) modal.classList.add('active');
}

function closeLightbox(e) {
  if (e.target.id === 'lightboxModal') {
    document.getElementById('lightboxModal').classList.remove('active');
  }
}

function closeLightboxDirect() {
  const modal = document.getElementById('lightboxModal');
  if (modal) modal.classList.remove('active');
}

// 5. Auth Tab Switcher
function switchAuthTab(mode) {
  const loginBtn = document.getElementById('tabLoginBtn');
  const regBtn = document.getElementById('tabRegisterBtn');
  const loginPanel = document.getElementById('loginPanel');
  const regPanel = document.getElementById('registerPanel');

  if (!loginBtn || !regBtn || !loginPanel || !regPanel) return;

  if (mode === 'login') {
    loginBtn.classList.add('active');
    regBtn.classList.remove('active');
    loginPanel.classList.add('active');
    regPanel.classList.remove('active');
  } else {
    regBtn.classList.add('active');
    loginBtn.classList.remove('active');
    regPanel.classList.add('active');
    loginPanel.classList.remove('active');
  }
}

// 6. Exact 30-Seat Matrix Engine (Rows A-D)
let currentMovie = "FOOD COMBO (EVIL DEAD BURN)";
let currentHall = "Auditorium 1 (Giant Screen)";
let selectedSeats = [];
let selectedPayment = "bKash";

const seatConfig = [
  { row: 'A', count: 8, type: 'standard' },
  { row: 'B', count: 8, type: 'standard' },
  { row: 'C', count: 8, type: 'vip' },
  { row: 'D', count: 3, type: 'couple' }
];

const occupiedSeats = ['A3', 'B5', 'C4', 'D2'];

const SEAT_PRICING = {
  standard: 400.00,
  vip: 500.00,
  couple: 900.00
};

function initSeatMatrix() {
  const grid = document.getElementById('ifsCinemaSeatingGrid') || document.getElementById('seatingGrid');
  if (!grid) return;
  grid.innerHTML = '';

  seatConfig.forEach(cfg => {
    const rowEl = document.createElement('div');
    rowEl.className = 'ifs-cinema-seat-row';

    const labelLeft = document.createElement('div');
    labelLeft.className = 'ifs-cinema-row-label';
    labelLeft.innerText = cfg.row;
    rowEl.appendChild(labelLeft);

    if (cfg.type === 'couple') {
      for (let i = 1; i <= cfg.count; i++) {
        const seatId = `D${i * 2 - 1}-D${i * 2}`;
        const seatLabelText = `${i * 2 - 1}-${i * 2}`;
        
        const seatEl = document.createElement('button');
        seatEl.type = 'button';
        seatEl.className = 'ifs-cinema-seat-node couple';
        seatEl.innerHTML = `<span class="ifs-cinema-seat-number">${seatLabelText}</span>`;
        if (i === 2) seatEl.classList.add('aisle');

        if (occupiedSeats.includes(`D${i}`)) {
          seatEl.classList.add('ifs-cinema-occupied', 'occupied');
          seatEl.disabled = true;
        } else {
          seatEl.onclick = () => toggleSeatSelection(seatId, seatEl, 'couple');
        }
        rowEl.appendChild(seatEl);
      }
    } else {
      for (let i = 1; i <= cfg.count; i++) {
        const seatId = `${cfg.row}${i}`;
        const seatNumber = `${i}`;

        const seatEl = document.createElement('button');
        seatEl.type = 'button';
        seatEl.className = 'ifs-cinema-seat-node';
        if (cfg.type === 'vip') seatEl.classList.add('vip');
        seatEl.innerHTML = `<span class="ifs-cinema-seat-number">${seatNumber}</span>`;

        if (i === 4) seatEl.classList.add('aisle');

        if (occupiedSeats.includes(seatId)) {
          seatEl.classList.add('ifs-cinema-occupied', 'occupied');
          seatEl.disabled = true;
        } else {
          seatEl.onclick = () => toggleSeatSelection(seatId, seatEl, cfg.type);
        }
        rowEl.appendChild(seatEl);
      }
    }

    const labelRight = document.createElement('div');
    labelRight.className = 'ifs-cinema-row-label';
    labelRight.innerText = cfg.row;
    rowEl.appendChild(labelRight);

    grid.appendChild(rowEl);
  });
}

function toggleSeatSelection(seatId, el, type) {
  const price = SEAT_PRICING[type];
  const index = selectedSeats.findIndex(s => s.id === seatId);

  if (index > -1) {
    selectedSeats.splice(index, 1);
    el.classList.remove('selected');
  } else {
    selectedSeats.push({ id: seatId, price: price });
    el.classList.add('selected');
  }

  updateSeatPricing();
}

function updateSeatPricing() {
  const countEl = document.getElementById('ifsSeatCount') || document.getElementById('seatCount');
  const chipsEl = document.getElementById('ifsCinemaChipsContainer') || document.getElementById('chipsContainer') || document.getElementById('ifsChipsWrapper');
  const subtotalEl = document.getElementById('ifsSubtotal') || document.getElementById('subtotalPrice');
  const taxEl = document.getElementById('ifsTax') || document.getElementById('taxPrice');
  const grandEl = document.getElementById('ifsGrandTotal') || document.getElementById('grandTotalPrice');

  if (countEl) countEl.innerText = selectedSeats.length;

  if (chipsEl) {
    if (selectedSeats.length === 0) {
      chipsEl.innerHTML = `<span style="font-size:0.85rem; color:var(--ifs-cinema-text-muted);">Click seats to select...</span>`;
    } else {
      chipsEl.innerHTML = selectedSeats.map(s => `<span class="ifs-cinema-seat-chip">${s.id}</span>`).join('');
    }
  }

  const subtotal = selectedSeats.reduce((acc, s) => acc + s.price, 0);
  const tax = subtotal * 0.05;
  const grand = subtotal + tax;

  if (subtotalEl) subtotalEl.innerText = `BDT ${subtotal.toFixed(2)}`;
  if (taxEl) taxEl.innerText = `BDT ${tax.toFixed(2)}`;
  if (grandEl) grandEl.innerText = `BDT ${grand.toFixed(2)}`;
}

function ifsSelectPayment(btn, method) {
  document.querySelectorAll('.ifs-cinema-pay-btn').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
  selectedPayment = method;
}

function ifsCinemaOpenSeatModal(movie, hall, price) {
  currentMovie = movie;
  currentHall = hall;
  selectedSeats = [];

  const titleEl = document.getElementById('ifsCinemaModalTitle');
  const subEl = document.getElementById('ifsCinemaModalSubtitle');
  const modalEl = document.getElementById('ifsCinemaSeatModal');

  if (titleEl) titleEl.innerText = movie;
  if (subEl) subEl.innerText = `${hall} • 30-Seat VIP Boutique`;
  if (modalEl) modalEl.classList.add('ifs-cinema-active');

  initSeatMatrix();
  updateSeatPricing();
}

function ifsCinemaCloseSeatModal() {
  const modalEl = document.getElementById('ifsCinemaSeatModal');
  if (modalEl) modalEl.classList.remove('ifs-cinema-active');
}

function ifsProceedToTicketPass() {
  if (selectedSeats.length === 0) {
    alert('Please pick at least one seat.');
    return;
  }

  const grandEl = document.getElementById('ifsGrandTotal') || document.getElementById('grandTotalPrice');
  const grand = grandEl ? grandEl.innerText : 'BDT 0.00';
  const token = '#BSC-' + Math.floor(1000 + Math.random() * 9000) + '-TX';

  const mEl = document.getElementById('ifsPassMovie');
  const hEl = document.getElementById('ifsPassHall');
  const sEl = document.getElementById('ifsPassSeats');
  const tEl = document.getElementById('ifsPassTotal');
  const tokEl = document.getElementById('ifsPassToken');

  if (mEl) mEl.innerText = currentMovie;
  if (hEl) hEl.innerText = currentHall;
  if (sEl) sEl.innerText = selectedSeats.map(s => s.id).join(', ');
  if (tEl) tEl.innerText = `${selectedPayment} • ${grand}`;
  if (tokEl) tokEl.innerText = `PASS TOKEN: ${token}`;

  ifsCinemaCloseSeatModal();
  const passModal = document.getElementById('ifsCinemaPassModal');
  if (passModal) passModal.classList.add('ifs-cinema-active');
}

// 7. Initialization
window.addEventListener('DOMContentLoaded', () => {
  initActiveNavigation();
  initSeatMatrix();
});