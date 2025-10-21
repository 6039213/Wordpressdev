<?php
/**
 * Schoolbord Restaurant Front Page
 * 
 * @package RestaurantPro
 * @version 2.0
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Schoolbord</h1>
        <p class="hero-subtitle">restaurant Leiden</p>
        <p class="hero-description">
            Kom dineren bij restaurant Schoolbord, waar onze studenten, de professionals van de toekomst, aan het werk zijn.<br>
            Dit fraaie restaurant biedt ruimte aan 32 gasten en heeft een modern industriële uitstraling en mooie aangeklede tafels.
        </p>
        <p class="hero-invitation">
            Wij nodigen u van harte uit op de entresol van <strong>schoolgebouw Lammenschans</strong> om te komen genieten van een heerlijk diner.
        </p>
        <div class="hero-cta">
            <a href="#reservation-form" class="btn btn-primary">
                <i class="dashicons dashicons-calendar-alt"></i>
                Nu reserveren
            </a>
        </div>
    </div>
</section>

<!-- Restaurant Info Section -->
<section class="section restaurant-info-highlight">
    <div class="container">
        <div class="info-cards">
            <div class="info-card">
                <div class="info-icon"><span class="dashicons dashicons-welcome-learn-more" aria-hidden="true"></span></div>
                <h3>Kom dineren op maandag, woensdag en donderdag</h3>
            </div>
            <div class="info-card">
                <div class="info-icon"><span class="dashicons dashicons-carrot" aria-hidden="true"></span></div>
                <h3>3-gangen menu voor &amp;euro;17,50</h3>
            </div>
            <div class="info-card">
                <div class="info-icon"><span class="dashicons dashicons-clock" aria-hidden="true"></span></div>
                <h3>Aanvang tussen 17.15 en 17.30 uur</h3>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="section">
    <div class="container">
        <div class="grid grid-2">
            <div class="about-content">
                <h2 class="section-title">Over Restaurant Schoolbord</h2>
                
                <p>Kom langs en geniet van een 3-gangen verrassingsmenu voor <strong>&euro;17,50 exclusief drankjes</strong>.</p>
                
                <p>Voor het diner is het restaurant vanaf <strong>17.15 uur</strong> geopend en serveren de studenten vanaf <strong>17.30 uur</strong> de eerste gang.</p>
                
                <div class="highlight-box">
                    <h3>Belangrijke informatie</h3>
                    <p>Onze diners zijn in de eerste plaats de praktijkles voor onze studenten. Deze lessen starten en eindigen op vaste tijden. Wij vragen daarom hiervoor uw begrip en verzoeken u tijdig aanwezig te zijn.</p>
                </div>
                
                <h3>Praktische zaken</h3>
                <ul class="info-list">
                    <li><strong>Diner is mogelijk op:</strong> Elke maandag, woensdag of donderdag</li>
                    <li><strong>Betalen:</strong> U kunt bij ons alleen pinnen. Mocht u een tip willen dan kan dit alleen via pin. Hiervan worden excursies voor studenten betaald.</li>
                    <li><strong>Inloop:</strong> Tussen 17.15 en 17.30 uur</li>
                    <li><strong>Einde service:</strong> Om 19.30 uur is het einde van de service</li>
                    <li><strong>Sluiting:</strong> De les van onze studenten is afgelopen om 20.00 uur. Op dit tijdstip sluit het restaurant.</li>
                    <li><strong>Reserveren:</strong> Voor het diner kunt u op deze pagina online reserveren</li>
                </ul>
            </div>
            
            <div class="dietary-info">
                <div class="info-box">
                    <h3>Let op: Dieetwensen</h3>
                    <p>Uiteraard kunnen we, in bepaalde mate, rekening houden met dieetwensen.</p>
                    
                    <p>Er kan aangepast gekookt worden waarbij rekening wordt gehouden met de volgende wensen:</p>
                    <ul>
                        <li>&ndash; geen gluten</li>
                        <li>&ndash; geen lactose</li>
                        <li>&ndash; geen noten/zaden</li>
                        <li>&ndash; vegetarisch/veganisme</li>
                    </ul>
                    
                    <p class="note"><strong>Mocht met een van bovenstaande wensen rekening gehouden worden, geef dit dan s.v.p. bij de reservering door.</strong></p>
                </div>
                
                <div class="info-box warning-box">
                    <h3>Schoolvakanties</h3>
                    <p><strong>Tevens maken wij u erop attent dat we tijdens de schoolvakanties gesloten zijn.</strong></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reservation Form Section -->
<section class="section reservation-section" id="reservation-form">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Online reserveren</h2>
            <p class="section-subtitle">Reserveer uw tafel bij Restaurant Schoolbord</p>
        </div>
        
        <div class="reservation-form">
            <form id="restaurant-reservation-form" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="reservation-name">Naam <span class="required">*</span></label>
                        <input type="text" id="reservation-name" name="reservation_name" required>
                    </div>
                    <div class="form-group">
                        <label for="reservation-email">E-mailadres <span class="required">*</span></label>
                        <input type="email" id="reservation-email" name="reservation_email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="reservation-phone">Telefoonnummer <span class="required">*</span></label>
                        <input type="tel" id="reservation-phone" name="reservation_phone" required>
                    </div>
                    <div class="form-group">
                        <label for="reservation-guests">Aantal personen <span class="required">*</span></label>
                        <select id="reservation-guests" name="reservation_guests" required>
                            <option value="">Selecteer aantal personen</option>
                            <?php for ($i = 1; $i <= 12; $i++) : ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> <?php echo $i == 1 ? 'persoon' : 'personen'; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="reservation-date">Datum <span class="required">*</span></label>
                        <input type="date" id="reservation-date" name="reservation_date" required>
                        <small class="form-help">Diner is mogelijk op maandag, woensdag en donderdag</small>
                    </div>
                    <div class="form-group">
                        <label for="reservation-time">Aankomsttijd <span class="required">*</span></label>
                        <select id="reservation-time" name="reservation_time" required>
                            <option value="">Selecteer tijd</option>
                            <option value="17:15">17:15 uur</option>
                            <option value="17:20">17:20 uur</option>
                            <option value="17:25">17:25 uur</option>
                            <option value="17:30">17:30 uur</option>
                        </select>
                        <small class="form-help">Inloop tussen 17.15 en 17.30 uur</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="reservation-dietary">Dieetwensen / Bijzonderheden</label>
                    <textarea id="reservation-dietary" name="reservation_message" rows="4" placeholder="Vermeld hier uw dieetwensen (geen gluten, geen lactose, geen noten/zaden, vegetarisch/veganisme)"></textarea>
                    <small class="form-help">Geef eventuele dieetwensen of allergieën door</small>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="dashicons dashicons-calendar-alt"></i>
                        Reservering plaatsen
                    </button>
                </div>
                
                <div class="reservation-info">
                    <p><small>* Verplichte velden</small></p>
                    <p><small>U ontvangt een bevestiging van uw reservering per e-mail.</small></p>
                    <p><small><strong>Let op:</strong> Tijdens schoolvakanties is het restaurant gesloten.</small></p>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Contact Info Section -->
<section class="section" style="background: var(--color-light-gray);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Bezoek ons</h2>
            <p class="section-subtitle">Restaurant Schoolbord Leiden</p>
        </div>
        
        <div class="grid grid-3">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="dashicons dashicons-location"></i>
                </div>
                <h3>Locatie</h3>
                <p>Schoolgebouw Lammenschans<br>Leiden</p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="dashicons dashicons-clock"></i>
                </div>
                <h3>Openingstijden</h3>
                <p>Maandag, Woensdag & Donderdag<br>17:15 - 20:00 uur</p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="dashicons dashicons-food"></i>
                </div>
                <h3>Menu</h3>
                <p>3-gangen verrassingsmenu<br>&euro;17,50 (excl. drankjes)</p>
            </div>
        </div>
    </div>
</section>

<?php locate_template('template-parts/home-dynamic.php', true, false); ?>

<style>
/* Schoolbord Specific Styles */
.hero-section {
    background: var(--gradient-dark);
    background-image: linear-gradient(rgba(74, 26, 92, 0.9), rgba(107, 45, 125, 0.9));
    min-height: 85vh;
    color: var(--color-white);
}

.hero-title {
    font-size: 4rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.hero-subtitle {
    font-size: 2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-description {
    font-size: 1.1rem;
    line-height: 1.8;
    max-width: 800px;
    margin: 0 auto 1.5rem;
}

.hero-invitation {
    font-size: 1.1rem;
    line-height: 1.8;
    max-width: 700px;
    margin: 0 auto 2rem;
}

.restaurant-info-highlight {
    background: linear-gradient(135deg, #f8f4fa 0%, #e9d5f2 100%);
    padding: 3rem 0;
}

.info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.info-card {
    background: var(--color-white);
    padding: 2rem;
    border-radius: var(--radius-lg);
    text-align: center;
    box-shadow: 0 4px 20px rgba(107, 45, 125, 0.1);
    border: 2px solid var(--color-secondary);
}

.info-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.info-card h3 {
    color: var(--color-primary);
    font-size: 1.2rem;
    margin: 0;
}

.highlight-box {
    background: linear-gradient(135deg, #fff5fb 0%, #ffe6f5 100%);
    padding: 1.5rem;
    border-radius: var(--radius-md);
    border-left: 4px solid var(--color-secondary);
    margin: 2rem 0;
}

.highlight-box h3 {
    color: var(--color-primary);
    margin-top: 0;
}

.info-list {
    list-style: none;
    padding: 0;
}

.info-list li {
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--color-border);
}

.info-list li:last-child {
    border-bottom: none;
}

.info-box {
    background: var(--color-white);
    padding: 2rem;
    border-radius: var(--radius-lg);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    border: 2px solid var(--color-border);
}

.info-box h3 {
    color: var(--color-primary);
    margin-top: 0;
}

.info-box ul {
    list-style: none;
    padding-left: 0;
}

.info-box ul li {
    padding: 0.3rem 0;
}

.note {
    background: #fff9e6;
    padding: 1rem;
    border-radius: var(--radius-sm);
    border-left: 4px solid #ffd700;
}

.warning-box {
    background: linear-gradient(135deg, #fff0f5 0%, #ffe4f0 100%);
    border-color: var(--color-secondary);
}

.form-help {
    display: block;
    color: var(--color-text-light);
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

.contact-card {
    background: var(--color-white);
    padding: 2rem;
    border-radius: var(--radius-lg);
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--color-border);
}

.contact-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient-primary);
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.contact-icon .dashicons {
    font-size: 24px;
    color: var(--color-white);
}

.contact-card h3 {
    margin-bottom: 0.5rem;
    color: var(--color-primary);
}

.contact-card p {
    margin: 0;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.5rem;
    }
    
    .hero-description,
    .hero-invitation {
        font-size: 1rem;
    }
    
    .grid-2 {
        grid-template-columns: 1fr;
    }
}
</style>

<?php get_footer(); ?>
