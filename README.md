# 🏗️ Valori Venali Aree Fabbricabili

Applicativo web per il calcolo della **Stima dei Valori Venali delle Aree Fabbricabili**, basato sui valori OMI (Osservatorio del Mercato Immobiliare) dell'Agenzia delle Entrate.

## Stack Tecnologico

| Layer | Tecnologia |
|-------|-----------|
| Frontend | Bootstrap Italia v2 (CDN) |
| Backend | PHP 8.2 |
| Web Server | Apache 2.4 |
| Database | MariaDB 11 |
| Container | Docker Compose |

## 🚀 Avvio Rapido

```bash
# 1. Clona il repository
git clone <repo-url>
cd Valori.Venali

# 2. Configura l'ambiente
cp .env.example .env
# Modifica .env con i dati del tuo Comune

# 3. Avvia i container
docker compose up -d --build

# 4. Apri il browser
open http://localhost:8080
```

## 📋 Configurazione `.env`

| Variabile | Descrizione | Default |
|-----------|-------------|---------|
| `DB_PASS` | Password MariaDB | `changeme123` |
| `APP_ADMIN_USER` | Username amministratore | `admin` |
| `APP_ADMIN_PASS` | Password amministratore | `changeme123` |
| `APP_SECRET` | Chiave sessioni (≥ 32 char) | — |
| `COMUNE_NOME` | Nome ente nell'header | `Comune di ...` |
| `APP_PORT` | Porta HTTP esposta | `8080` |

> ⚠️ **Cambia sempre** `DB_PASS`, `APP_ADMIN_PASS` e `APP_SECRET` prima di andare in produzione.

## 🗂️ Struttura

```
├── docker-compose.yml
├── .env                   # Segreti locali (gitignored)
├── .env.example           # Template
├── docker/php/Dockerfile  # PHP 8.2 + Apache
├── initdb/01_schema.sql   # Schema + seed DB
└── src/                   # Document root Apache
    ├── index.php           # Calcolo stima (pagina pubblica)
    ├── login.php
    ├── logout.php
    ├── includes/           # Config, DB PDO, Auth
    ├── layout/             # Header/Footer Bootstrap Italia
    └── admin/              # Area amministrativa
        ├── dashboard.php
        ├── importa_omi.php
        ├── parametri_omi.php
        ├── coefficienti_abbattimento.php
        └── fogli_omi.php
```

## 📐 Formula di Calcolo

```
Valore Venale = Superficie (mq) × Valore OMI (€/mq) × Coefficiente Destinazione × Coefficiente Abbattimento
```

## 📂 Import Dati OMI

I valori OMI si importano tramite l'area admin (`/admin/importa_omi.php`) caricando il file CSV esportato dal **Portale Sister** dell'Agenzia delle Entrate.

Formato atteso:
```
- 2° Semestre - 2024
Zona;Cod_Tip;Descr_Tipologia;Stato;Compr_min;Compr_max
...
```

## 🐳 Comandi Docker Utili

```bash
# Vedere i log
docker compose logs -f

# Accedere al DB
docker compose exec db mariadb -u vvenali -pchangeme123 valori_venali

# Ricostruire dopo modifiche al Dockerfile
docker compose up -d --build

# Stop e rimozione volumi
docker compose down -v
```

## 📜 Riferimenti Normativi

- **D.L. 504/1992** — Determinazione valore venale aree edificabili ai fini ICI/IMU
- **OMI** — Osservatorio del Mercato Immobiliare, Agenzia delle Entrate
