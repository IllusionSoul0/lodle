<script setup></script>

<template>
  <nav class="game-buttons">
    <router-link to="/classic" class="button-game">
      <img src="/src/assets/icons/classic_mode.png" alt="classic" class="button-img" />
      <div class="button-overlay">
        <div class="button-text">
          <div class="button-title">Mode Classique</div>
          <div class="button-description">Trouvez le champion avec des indices</div>
        </div>
      </div>
    </router-link>

    <router-link to="/quote" class="button-game">
      <img src="/src/assets/icons/quote_mode.png" alt="quote" class="button-img" />
      <div class="button-overlay">
        <div class="button-text">
          <div class="button-title">Mode Citation</div>
          <div class="button-description">Devinez qui a dit cette citation</div>
        </div>
      </div>
    </router-link>

    <router-link to="/splash" class="button-game">
      <img src="/src/assets/icons/splash_mode.png" alt="splash" class="button-img" />
      <div class="button-overlay">
        <div class="button-text">
          <div class="button-title">Mode Splash Art</div>
          <div class="button-description">Reconnaître le champion par son splash</div>
        </div>
      </div>
    </router-link>
  </nav>
</template>

<style>
.game-buttons {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2rem;
  padding: 2rem;
}

.button-game {
  cursor: pointer;
  font-weight: 600;
  text-decoration: none;
  width: 100%;
  max-width: 370px;
  overflow: hidden;
  position: relative;
  border-radius: 15px;
  transition: transform 0.3s ease;
}

.button-game:hover {
  transform: scale(1.03);
}

.button-img {
  width: 100%;
  display: block;
  height: auto;
  filter: brightness(0.6);
  transition: filter 0.3s ease;
}

.button-game:hover .button-img {
  filter: brightness(0.8);
}

.button-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  justify-content: flex-end; 
  align-items: center;  
  padding-right: 1rem;   
  pointer-events: none; 
  z-index: 2;
}

.button-text {
  display: flex;
  flex-direction: column;
  text-align: right;
  color: #f0e6d2;
  transition: transform 0.3s ease;
}

.button-game:hover .button-text {
  transform: scale(1.1);
}

.button-title {
  font-size: 1.3rem;
  font-weight: bold;
  color: #c89b3c;
  text-transform: uppercase;
  letter-spacing: 1px;
  text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
}

.button-description {
  font-size: 0.85rem;
  color: #a09b8c;
  text-shadow: 0 1px 4px rgba(0, 0, 0, 0.8);
}

@media (max-width: 768px) {
  .game-buttons {
    gap: 1.5rem;
    padding: 1.5rem;
  }

  .button-game {
    max-width: 100%;
    width: 90%;
  }

  .button-title {
    font-size: 1.2rem;
  }

  .button-description {
    font-size: 0.8rem;
  }

  .button-overlay {
    padding-right: 0.5rem;
  }
}

@media (max-width: 480px) {
  .game-buttons {
    gap: 1rem;
    padding: 1rem;
    margin-top: 1rem;
  }

  .button-game {
    width: 95%;
    max-width: none;
  }

  .button-title {
    font-size: 1rem;
  }

  .button-description {
    font-size: 0.75rem;
  }

  .button-overlay {
    padding-right: 0.5rem;
  }
}
</style>
