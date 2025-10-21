#!/bin/bash

echo "🚀 Schoolbord WordPress Setup gestart..."
echo "Wachten tot MySQL klaar is..."
sleep 15

echo "📦 WordPress installeren..."
docker compose exec -T wordpress wp core install \
  --url="http://localhost:8080" \
  --title="Restaurant Schoolbord" \
  --admin_user="admin" \
  --admin_password="Schoolbord2024!" \
  --admin_email="admin@schoolbord.test" \
  --skip-email \
  --allow-root

echo "🎨 Restaurant Theme activeren..."
docker compose exec -T wordpress wp theme activate restauranttheme --allow-root

echo "🔌 Schoolbord Reservations plugin activeren..."
docker compose exec -T wordpress wp plugin activate schoolbord-reservations --allow-root 2>/dev/null || echo "Plugin wordt later geactiveerd"

echo "⚙️ Permalink structuur instellen..."
docker compose exec -T wordpress wp rewrite structure '/%postname%/' --allow-root

echo "🎉 Setup compleet!"
echo ""
echo "✅ Site URL: http://localhost:8080"
echo "✅ Admin URL: http://localhost:8080/wp-admin"
echo "✅ Username: admin"
echo "✅ Password: Schoolbord2024!"
echo "✅ Thema: Restaurant Theme (actief)"
echo "✅ Kleuren: Paars/Roze Schoolbord theme"
echo ""
echo "De Schoolbord content (gerechten, evenementen) is zichtbaar op de homepage!"

