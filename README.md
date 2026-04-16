<div align="center">

# 🚀 A Pod's Journey
### *From Development to Production — A Hands-On LAMP Stack Adventure*

> Deploy a full LAMP application using **Podman** (rootless), orchestrate it with **Kubernetes (k3s)**,
> and automate everything with **GitHub Actions CI/CD**.

</div>

---

## 🗺️ The Journey

```text
🧑‍💻 Developer              ⚙️ Pipeline                  🛡️ SysAdmin
─────────────────         ─────────────────         ─────────────────
Clone → Code → Test  ───► Lint → Build → Scan  ───► Deploy → Scale → HA
VS Code + Podman           GitHub Actions            k3s + Traefik

</div>

                  ┌─────────────────────────────────────┐
PHASE 1           │  Podman Pod (rootless)              │
Local Dev         │  ┌──────────┐  ┌─────────────────┐  │
                  │  │ MariaDB  │  │   PHP/Apache    │  │
                  │  │  :3306   │  │     :8080       │  │
                  │  └──────────┘  └─────────────────┘  │
                  │  ┌─────────────────────────────┐    │
                  │  │     phpMyAdmin :8081        │    │
                  │  └─────────────────────────────┘    │
                  └─────────────────────────────────────┘
                                     │
                               git push main
                                     │
                  ┌─────────────────────────────────────┐
CI/CD             │  GitHub Actions                     │
Pipeline          │  ① Lint (phpcs + phpstan)           │
                  │  ② Build image                      │
                  │  ③ Security scan (Trivy)            │
                  │  ④ Push → ghcr.io                   │
                  │  ⑤ Deploy → k3s                     │
                  └─────────────────────────────────────┘
                                     │
                  ┌─────────────────────────────────────┐
PHASE 2           │  k3s Cluster  [namespace: lamp]     │
Production        │                                     │
                  │  MariaDB ──── PHP App (×3) ─── PMA  │
                  │   1 pod       HPA 2-10       1 pod  │
                  │   PVC 5G      RollingUpdate         │
                  │                                     │
                  │         Traefik Ingress             │
                  │    empresa.local / pma.empresa.local│
                  └─────────────────────────────────────┘
