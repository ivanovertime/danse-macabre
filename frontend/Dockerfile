FROM node:22-alpine

ENV CI=true

RUN corepack enable && corepack prepare pnpm@latest --activate

WORKDIR /app

COPY package.json pnpm-lock.yaml pnpm-workspace.yaml ./

RUN pnpm install --frozen-lockfile 2>/dev/null || pnpm install

COPY . .

EXPOSE 3000

CMD ["pnpm", "dev"]
