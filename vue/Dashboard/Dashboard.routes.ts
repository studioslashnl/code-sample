// Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills. // This code may not be used or distributed for any purpose. import { RouteConfig } from
'vue-router'; import { loadDashboardData } from './guards/load-dashboard.guard'; import DashboardView from '@module/dashboard/views/dashboard.vue'; export const DashboardRoutes:
RouteConfig[] = [ { path: '/dashboard', name: 'dashboard', component: DashboardView, beforeEnter: loadDashboardData, meta: { title: 'Robin - Dashboard', }, }, ];
