<?php include ('header.php');?>

  <!-- 01. SINGLE MOVIE IMMERSIVE BACKDROP HERO -->
  <section class="ifs-cinema-single-movie-hero">
    <div class="ifs-cinema-container ifs-cinema-movie-header-grid">
      
      <!-- Poster Frame -->
      <div class="ifs-cinema-poster-frame-3d">
        <img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=700&auto=format&fit=crop&q=75" alt="Evil Dead Burn Movie Poster">
      </div>

      <!-- Specs & Overview -->
      <div>
        <div style="display:flex; gap:0.65rem; margin-bottom:1.25rem;">
          <span class="ifs-cinema-badge ifs-cinema-badge-amber">BOUTIQUE AUDITORIUM (23 SEATS)</span>
          <span class="ifs-cinema-badge ifs-cinema-badge-imax">4K DUAL LASER</span>
        </div>

        <h1 class="ifs-cinema-hero-main-title">FOOD COMBO (EVIL DEAD BURN)</h1>

        <div class="ifs-cinema-specs-hud-bar">
          <div class="ifs-cinema-hud-item highlight">
            <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" style="display:inline-block; vertical-align:-1px; margin-right:2px;">
              <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
            8.6 / 10 Master Rating
          </div>
          <div class="ifs-cinema-hud-item">01 Hours 50 Minutes</div>
          <div class="ifs-cinema-hud-item">Horror / Supernatural Thriller</div>
          <div class="ifs-cinema-hud-item">English (Dolby Atmos® 128-Ch)</div>
          <div class="ifs-cinema-hud-item">R-Rated (18+)</div>
        </div>

        <p style="font-size:1.15rem; line-height:1.8; color:var(--ifs-cinema-text-secondary); max-width:680px;">
          Fight fire with fire. Experience our signature dine-in package: Get a complimentary movie admission ticket included with every purchase of our chef-prepared Crispy Chicken, Gourmet Butter Popcorn tub, and fountain beverage.
        </p>

        <!-- Credit Key-Values -->
        <div class="ifs-cinema-movie-credits-grid">
          <div class="ifs-cinema-credit-box">
            <div class="key">Director</div>
            <div class="val">Darren Aron</div>
          </div>
          <div class="ifs-cinema-credit-box">
            <div class="key">Writers</div>
            <div class="val">Sam Raimi, James Wan</div>
          </div>
          <div class="ifs-cinema-credit-box">
            <div class="key">Release Date</div>
            <div class="val">July 10, 2026</div>
          </div>
        </div>

        <div style="display:flex; gap:1.25rem; align-items:center;">
          <button class="ifs-cinema-btn ifs-cinema-btn-amber" onclick="document.getElementById('seatSelectorSection').scrollIntoView({behavior:'smooth'})">
            Choose Seats Below (From BDT 400)
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
              <path d="M13 5v11.86l5.43-5.43 1.42 1.42L12 21.14l-7.85-8.29 1.42-1.42L11 16.86V5h2z"/>
            </svg>
          </button>
          <button class="ifs-cinema-btn ifs-cinema-btn-secondary" onclick="document.getElementById('trailerBlock').scrollIntoView({behavior:'smooth'})">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
              <path d="M8 5v14l11-7z"/>
            </svg>
            Watch 4K Trailer
          </button>
        </div>
      </div>

    </div>
  </section>

  <!-- 02. EXACT CINEMA HALL SEAT MAP (MOVED BEFORE STORYTELLING) -->
  <section class="ifs-cinema-container ifs-cinema-inline-seat-section" id="seatSelectorSection" style="padding-top: 3.5rem;">
    
    <div style="margin-bottom: 2rem; text-align: center;">
      <span class="ifs-cinema-badge ifs-cinema-badge-amber">BOUTIQUE 23-SEAT HALL</span>
      <h2 style="font-size: 2.2rem; font-weight: 900; margin-top: 0.5rem;">Cinema Hall Seat Map</h2>
    </div>

    <div class="ifs-cinema-stage-card">
      
      <!-- Seating Canvas Area -->
      <div class="ifs-cinema-seating-arena">
        
        <div class="ifs-cinema-map-layout-wrapper">
          <!-- Left VIP Boundary Bar -->
          <div class="ifs-cinema-boundary-line left"></div>

          <!-- 4 Vertical Column Seating Matrix -->
          <div class="ifs-cinema-columns-matrix" id="seatingColumnsGrid" role="grid" aria-label="Vertical Columns Seat Map">
            
            <!-- Column 1: Row A (VIP Recliner Column) -->
            <div class="ifs-cinema-seat-column vip-col">
              <div class="ifs-cinema-column-seats">
                <button type="button" class="ifs-cinema-seat-box vip" id="seat-A1" onclick="toggleSeat('A1', this, 'vip')">
                  <span class="ifs-seat-id">A1</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box vip" id="seat-A2" onclick="toggleSeat('A2', this, 'vip')">
                  <span class="ifs-seat-id">A2</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box vip occupied" id="seat-A3" disabled>
                  <span class="ifs-seat-id">A3</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box vip" id="seat-A4" onclick="toggleSeat('A4', this, 'vip')">
                  <span class="ifs-seat-id">A4</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box vip" id="seat-A5" onclick="toggleSeat('A5', this, 'vip')">
                  <span class="ifs-seat-id">A5</span>
                </button>
              </div>
              <div class="ifs-cinema-vip-col-label">VIP SEATING</div>
            </div>

            <!-- Col Label A -->
            <div class="ifs-cinema-col-divider-label">
              <span>A</span>
              <span>A</span>
            </div>

            <!-- Column 2: Row B (Standard Column) -->
            <div class="ifs-cinema-seat-column standard-col">
              <div class="ifs-cinema-column-seats">
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-B1" onclick="toggleSeat('B1', this, 'standard')">
                  <span class="ifs-seat-id">B1</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard occupied" id="seat-B2" disabled>
                  <span class="ifs-seat-id">B2</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-B3" onclick="toggleSeat('B3', this, 'standard')">
                  <span class="ifs-seat-id">B3</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-B4" onclick="toggleSeat('B4', this, 'standard')">
                  <span class="ifs-seat-id">B4</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-B5" onclick="toggleSeat('B5', this, 'standard')">
                  <span class="ifs-seat-id">B5</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-B6" onclick="toggleSeat('B6', this, 'standard')">
                  <span class="ifs-seat-id">B6</span>
                </button>
              </div>
            </div>

            <!-- Col Label Row B -->
            <div class="ifs-cinema-col-divider-label">
              <span>Row B</span>
              <span>Row B</span>
            </div>

            <!-- Column 3: Row C (Standard Column) -->
            <div class="ifs-cinema-seat-column standard-col">
              <div class="ifs-cinema-column-seats">
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-C1" onclick="toggleSeat('C1', this, 'standard')">
                  <span class="ifs-seat-id">C1</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-C2" onclick="toggleSeat('C2', this, 'standard')">
                  <span class="ifs-seat-id">C2</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-C3" onclick="toggleSeat('C3', this, 'standard')">
                  <span class="ifs-seat-id">C3</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-C4" onclick="toggleSeat('C4', this, 'standard')">
                  <span class="ifs-seat-id">C4</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard occupied" id="seat-C5" disabled>
                  <span class="ifs-seat-id">C5</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-C6" onclick="toggleSeat('C6', this, 'standard')">
                  <span class="ifs-seat-id">C6</span>
                </button>
              </div>
            </div>

            <!-- Col Label Row C & Row D -->
            <div class="ifs-cinema-col-divider-label">
              <span>Row D</span>
              <span>Row C</span>
            </div>

            <!-- Column 4: Row D (Standard Column Nearest to Screen) -->
            <div class="ifs-cinema-seat-column standard-col">
              <div class="ifs-cinema-column-seats">
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-D1" onclick="toggleSeat('D1', this, 'standard')">
                  <span class="ifs-seat-id">D1</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-D2" onclick="toggleSeat('D2', this, 'standard')">
                  <span class="ifs-seat-id">D2</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-D3" onclick="toggleSeat('D3', this, 'standard')">
                  <span class="ifs-seat-id">D3</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard occupied" id="seat-D4" disabled>
                  <span class="ifs-seat-id">D4</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-D5" onclick="toggleSeat('D5', this, 'standard')">
                  <span class="ifs-seat-id">D5</span>
                </button>
                <button type="button" class="ifs-cinema-seat-box standard" id="seat-D6" onclick="toggleSeat('D6', this, 'standard')">
                  <span class="ifs-seat-id">D6</span>
                </button>
              </div>
            </div>

          </div>

          <!-- Right Screen Indicator with Arrow -->
          <div class="ifs-cinema-screen-target-indicator">
            <div class="ifs-cinema-boundary-line right"></div>
            <div class="ifs-cinema-screen-pointer">
              <span class="title">SCREEN</span>
              <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14M13 5l7 7-7 7"/>
              </svg>
            </div>
          </div>
        </div>

        <!-- Tiered Legend -->
        <div class="ifs-cinema-legend">
          <div class="ifs-cinema-legend-item">
            <div class="ifs-cinema-legend-bullet" style="background: var(--ifs-cinema-seat-standard);"></div>
            <span>Standard Seat (৳400)</span>
          </div>
          <div class="ifs-cinema-legend-item">
            <div class="ifs-cinema-legend-bullet" style="background: var(--ifs-cinema-seat-vip);"></div>
            <span>VIP Recliner (৳500)</span>
          </div>
          <div class="ifs-cinema-legend-item">
            <div class="ifs-cinema-legend-bullet" style="background: var(--ifs-cinema-seat-selected);"></div>
            <span>Selected</span>
          </div>
          <div class="ifs-cinema-legend-item">
            <div class="ifs-cinema-legend-bullet" style="background: var(--ifs-cinema-seat-occupied); opacity: 0.35;"></div>
            <span>Occupied</span>
          </div>
        </div>
      </div>

      <!-- Checkout Summary Sidebar -->
      <div class="ifs-cinema-checkout-sidebar">
        <div>
          <div class="ifs-cinema-movie-meta-summary">
            <h3>FOOD COMBO (EVIL DEAD BURN)</h3>
            <span class="ifs-cinema-hall-tag">Hall Layout • 23 Seats Total</span>
          </div>

          <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--ifs-cinema-text-muted); margin-bottom: 0.5rem; display: block;">
            Selected Seats (<span id="seatCount">0</span>)
          </span>

          <div class="ifs-cinema-chips-wrapper" id="chipsContainer">
            <span style="font-size: 0.85rem; color: var(--ifs-cinema-text-muted);">Click seats to select...</span>
          </div>

          <!-- Structured Bill Breakdown Table -->
          <div class="ifs-cinema-bill-table-wrap" style="margin-bottom: 1.5rem; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; text-align: left;">
              <thead>
                <tr style="border-bottom: 1px solid var(--ifs-cinema-border-light);">
                  <th style="padding: 0.5rem 0; color: var(--ifs-cinema-text-muted); font-size: 0.72rem; text-transform: uppercase;">Description</th>
                  <th style="padding: 0.5rem 0; color: var(--ifs-cinema-text-muted); font-size: 0.72rem; text-transform: uppercase; text-align: right;">Amount</th>
                </tr>
              </thead>
              <tbody id="billTableBody">
                <tr style="border-bottom: 1px dashed var(--ifs-cinema-border-subtle);">
                  <td style="padding: 0.6rem 0; color: var(--ifs-cinema-text-secondary);">Tickets Base</td>
                  <td style="padding: 0.6rem 0; text-align: right; font-family: var(--ifs-cinema-font-mono); color: #fff;" id="subtotalPrice">BDT 0.00</td>
                </tr>
                <tr style="border-bottom: 1px dashed var(--ifs-cinema-border-subtle);">
                  <td style="padding: 0.6rem 0; color: var(--ifs-cinema-text-secondary);">VAT &amp; Service (5%)</td>
                  <td style="padding: 0.6rem 0; text-align: right; font-family: var(--ifs-cinema-font-mono); color: #fff;" id="taxPrice">BDT 0.00</td>
                </tr>
                <tr>
                  <td style="padding: 0.8rem 0; font-weight: 900; color: #fff;">Grand Total</td>
                  <td style="padding: 0.8rem 0; text-align: right; font-weight: 900; font-family: var(--ifs-cinema-font-mono); color: var(--ifs-cinema-brand-amber); font-size: 1.15rem;" id="grandTotalPrice">BDT 0.00</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <button type="button" class="ifs-cinema-btn ifs-cinema-btn-amber" style="width:100%;" onclick="confirmSeatBooking()">
          Confirm &amp; Issue Pass
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
            <path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-7.85-1.42 1.42L16.86 11H5v2z"/>
          </svg>
        </button>
      </div>

    </div>
  </section>

  <!-- 03. FULL DETAILS: SYNOPSIS, CAST & TRAILER -->
  <main class="ifs-cinema-container ifs-cinema-details-layout">
    
    <!-- Storyline Card -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/>
        </svg>
        Storyline &amp; Synopsis
      </h2>
      <p style="line-height:1.85; font-size:1.02rem; margin-bottom:1.5rem;">
        A secluded mountain sanctuary turns into a fiery gauntlet when an ancient relic is unearthed during a solar alignment. As malicious spirits manifest through thermal energy, survivors must use specialized sound arrays and counter-incendiary weapons to defend the threshold before the entire valley is consumed.
      </p>
      <p style="line-height:1.85; font-size:1.02rem;">
        Engineered natively for high-dynamic-range laser projection and multi-point subwoofer arrays, this screening leverages our custom 128-channel spatial audio profile to produce authentic low-end tactile response directly through the motorized VIP recliner frames.
      </p>
    </div>

    <!-- Lead Cast Card -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
        </svg>
        Lead Cast &amp; Production
      </h2>
      <div class="ifs-cinema-cast-deck">
        <div class="ifs-cinema-actor-card">
          <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=70" class="ifs-cinema-actor-photo" alt="Elena Rostova">
          <div class="ifs-cinema-actor-name">Elena Rostova</div>
          <div class="ifs-cinema-actor-role">Dr. Maya Vance</div>
        </div>
        <div class="ifs-cinema-actor-card">
          <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=70" class="ifs-cinema-actor-photo" alt="Marcus Cole">
          <div class="ifs-cinema-actor-name">Marcus Cole</div>
          <div class="ifs-cinema-actor-role">Captain Gabriel</div>
        </div>
        <div class="ifs-cinema-actor-card">
          <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&auto=format&fit=crop&q=70" class="ifs-cinema-actor-photo" alt="Darren Aron">
          <div class="ifs-cinema-actor-name">Darren Aron</div>
          <div class="ifs-cinema-actor-role">Director &amp; Producer</div>
        </div>
        <div class="ifs-cinema-actor-card">
          <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?w=200&auto=format&fit=crop&q=70" class="ifs-cinema-actor-photo" alt="Sarah Jenkins">
          <div class="ifs-cinema-actor-name">Sarah Jenkins</div>
          <div class="ifs-cinema-actor-role">Kira Sullivan</div>
        </div>
      </div>
    </div>

    <!-- 4K Trailer Box -->
    <div class="ifs-cinema-content-card" id="trailerBlock">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
        </svg>
        Official 4K Trailer Preview
      </h2>
      <div class="ifs-cinema-trailer-embed-card" onclick="alert('Playing 4K UHD Trailer stream with spatial audio...');">
        <div class="ifs-cinema-play-button-pulse">
          <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor" style="margin-left:3px;">
            <path d="M8 5v14l11-7z"/>
          </svg>
        </div>
      </div>
    </div>

  </main>

  <!-- INSTANT PASS MODAL VOUCHER -->
  <div class="ifs-cinema-modal-overlay" id="ticketPassModal">
    <div class="ifs-cinema-ticket-card">
      <button style="position: absolute; top: 14px; right: 14px; background: transparent; border: none; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 4px;" onclick="closeTicketModal()" aria-label="Close Modal">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
      </button>
      <div class="ifs-cinema-ticket-header">
        <h3 style="font-size: 1.35rem; font-weight: 900; letter-spacing: 1px;">BIG SCREEN PASS</h3>
        <p style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; margin-top: 2px;">Digital Admission Token</p>
      </div>
      <div class="ifs-cinema-ticket-body">
        <div class="ifs-cinema-ticket-grid">
          <div>
            <div style="font-size: 0.72rem; color: var(--ifs-cinema-text-muted); font-weight: 700; text-transform: uppercase;">Feature</div>
            <strong style="font-size: 0.95rem;">FOOD COMBO</strong>
          </div>
          <div>
            <div style="font-size: 0.72rem; color: var(--ifs-cinema-text-muted); font-weight: 700; text-transform: uppercase;">Auditorium</div>
            <strong style="font-size: 0.95rem;">Hall Layout (VIP)</strong>
          </div>
          <div>
            <div style="font-size: 0.72rem; color: var(--ifs-cinema-text-muted); font-weight: 700; text-transform: uppercase;">Seats</div>
            <strong id="passSeats" style="color: var(--ifs-cinema-brand-amber); font-family: var(--ifs-cinema-font-mono);">-</strong>
          </div>
          <div>
            <div style="font-size: 0.72rem; color: var(--ifs-cinema-text-muted); font-weight: 700; text-transform: uppercase;">Total Settled</div>
            <strong id="passTotal" style="font-family: var(--ifs-cinema-font-mono);">-</strong>
          </div>
        </div>

        <div class="ifs-cinema-ticket-qr">
          <div class="ifs-cinema-mock-qr"></div>
          <span style="font-size: 0.75rem; font-family: var(--ifs-cinema-font-mono); color: var(--ifs-cinema-brand-amber);" id="passToken">PASS: #BSC-0000-TX</span>
        </div>
      </div>
    </div>
  </div>

  <style>
    .ifs-cinema-map-layout-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1.5rem;
      width: 100%;
      overflow-x: auto;
      padding: 1.5rem 0.5rem;
    }

    .ifs-cinema-boundary-line {
      width: 3px;
      height: 380px;
      background: var(--ifs-cinema-border-light);
      border-radius: 2px;
      flex-shrink: 0;
    }

    .ifs-cinema-columns-matrix {
      display: flex;
      align-items: flex-start;
      gap: 1.25rem;
      user-select: none;
    }

    .ifs-cinema-seat-column {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.85rem;
    }

    .ifs-cinema-column-seats {
      display: flex;
      flex-direction: column;
      gap: 0.85rem;
    }

    .ifs-cinema-seat-box {
      width: 54px;
      height: 48px;
      border-radius: 8px 8px 4px 4px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: 1px solid rgba(255, 255, 255, 0.15);
      transition: var(--ifs-cinema-transition);
      background: var(--ifs-cinema-seat-standard);
      position: relative;
    }

    .ifs-cinema-seat-box.vip {
      width: 62px;
      height: 52px;
      background: var(--ifs-cinema-seat-vip);
      border-color: rgba(255, 157, 0, 0.5);
    }

    .ifs-cinema-seat-box:hover:not(.occupied) {
      transform: scale(1.08);
      border-color: var(--ifs-cinema-brand-amber);
    }

    .ifs-cinema-seat-box.selected {
      background: var(--ifs-cinema-brand-amber) !important;
      border-color: #fff !important;
      transform: scale(1.08);
    }

    .ifs-cinema-seat-box.selected .ifs-seat-id {
      color: #000 !important;
      font-weight: 900;
    }

    .ifs-cinema-seat-box.occupied {
      background: var(--ifs-cinema-seat-occupied) !important;
      border-color: transparent !important;
      opacity: 0.35;
      cursor: not-allowed;
    }

    .ifs-seat-id {
      font-family: var(--ifs-cinema-font-mono);
      font-weight: 800;
      font-size: 0.85rem;
      color: #fff;
      pointer-events: none;
    }

    .ifs-cinema-vip-col-label {
      font-size: 0.72rem;
      font-weight: 900;
      color: var(--ifs-cinema-brand-amber);
      letter-spacing: 0.08em;
      text-transform: uppercase;
      margin-top: 0.5rem;
      white-space: nowrap;
    }

    .ifs-cinema-col-divider-label {
      display: flex;
      flex-direction: column;
      justify-content: space-around;
      height: 340px;
      font-size: 0.82rem;
      font-weight: 800;
      color: var(--ifs-cinema-text-muted);
      font-family: var(--ifs-cinema-font-mono);
      padding: 0 0.25rem;
      white-space: nowrap;
    }

    .ifs-cinema-screen-target-indicator {
      display: flex;
      align-items: center;
      gap: 1rem;
      height: 380px;
    }

    .ifs-cinema-screen-pointer {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.35rem;
      color: var(--ifs-cinema-brand-amber);
    }

    .ifs-cinema-screen-pointer .title {
      font-size: 0.85rem;
      font-weight: 900;
      letter-spacing: 0.1em;
    }

    @media (max-width: 900px) {
      .ifs-cinema-map-layout-wrapper {
        justify-content: flex-start;
      }
    }
  </style>

  <script>
    let selectedSeats = [];

    const PRICES = {
      standard: 400.00,
      vip: 500.00
    };

    function toggleSeat(seatId, el, type) {
      const price = PRICES[type];
      const index = selectedSeats.findIndex(s => s.id === seatId);

      if (index > -1) {
        selectedSeats.splice(index, 1);
        el.classList.remove('selected');
      } else {
        selectedSeats.push({ id: seatId, price: price });
        el.classList.add('selected');
      }

      updatePricing();
    }

    function updatePricing() {
      const countEl = document.getElementById('seatCount');
      const chipsEl = document.getElementById('chipsContainer');
      const subtotalEl = document.getElementById('subtotalPrice');
      const taxEl = document.getElementById('taxPrice');
      const grandEl = document.getElementById('grandTotalPrice');

      if (countEl) countEl.innerText = selectedSeats.length;

      if (chipsEl) {
        if (selectedSeats.length === 0) {
          chipsEl.innerHTML = `<span style="font-size: 0.85rem; color: var(--ifs-cinema-text-muted);">Click seats to select...</span>`;
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

    function confirmSeatBooking() {
      if (selectedSeats.length === 0) {
        alert('Please pick at least one seat to proceed.');
        return;
      }

      const grand = document.getElementById('grandTotalPrice').innerText;
      const token = '#BSC-' + Math.floor(1000 + Math.random() * 9000) + '-TX';

      document.getElementById('passSeats').innerText = selectedSeats.map(s => s.id).join(', ');
      document.getElementById('passTotal').innerText = grand;
      document.getElementById('passToken').innerText = `PASS: ${token}`;

      document.getElementById('ticketPassModal').classList.add('active');
    }

    function closeTicketModal() {
      document.getElementById('ticketPassModal').classList.remove('active');
    }
  </script>

<?php include ('footer.php');?>