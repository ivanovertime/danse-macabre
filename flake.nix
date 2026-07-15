{
  description = "Danse Macabre — Laravel 13 + Nuxt 4 + PostgreSQL";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-unstable";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = nixpkgs.legacyPackages.${system};

        php = pkgs.php84.withExtensions ({ all, ... }: with all; [
          pdo_pgsql
          mbstring
          curl
          zip
          gd
          bcmath
          tokenizer
          fileinfo
          intl
          pcntl
          filter
          openssl
          zlib
          session
          dom
          simplexml
          xmlwriter
        ]);

      in {
        devShells.default = pkgs.mkShell {
          packages = [
            php
            pkgs.nodejs_22
            pkgs.pnpm
            pkgs.postgresql_17
            pkgs.git
            pkgs.jq
            pkgs.curl
          ];

          shellHook = ''
            echo "============================================="
            echo "  Danse Macabre — Development Environment"
            echo "============================================="

            # Install Composer locally if not present
            COMPOSER_BIN="$PWD/.nix-composer/composer"
            if [ ! -f "$COMPOSER_BIN" ]; then
              echo "  Installing Composer locally..."
              mkdir -p "$PWD/.nix-composer"
              curl -sS https://getcomposer.org/installer | php -- --install-dir="$PWD/.nix-composer" --filename=composer 2>/dev/null
            fi
            export PATH="$PWD/.nix-composer:$PATH"

            echo "  PHP:      $(php --version | head -n1)"
            echo "  Composer: $(composer --version)"
            echo "  Node:     $(node --version)"
            echo "  pnpm:     $(pnpm --version)"
            echo "============================================="

            export PATH="$PWD/backend/vendor/bin:$PWD/frontend/node_modules/.bin:$PATH"
          '';
        };
      });
}
