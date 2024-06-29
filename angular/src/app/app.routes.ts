import { Routes } from '@angular/router';
import { LandingComponent } from './pages/public/landing/landing.component';
import { LoginComponent } from './pages/public/login/login.component';
import { SignupComponent } from './pages/public/signup/signup.component';
import { NotFoundComponent } from './pages/public/not-found/not-found.component';
import { PublicLayoutComponent } from './layouts/public-layout/public-layout.component';
import { VerifyComponent } from './pages/public/verify/verify.component';
import { DashboardLayoutComponent } from './layouts/dashboard-layout/dashboard-layout.component';
import { DashboardComponent } from './pages/dashboard/dashboard/dashboard.component';
import { AuthGuard } from './guards/auth.guard';

export const routes: Routes = [
    {
        path: '',
        component: PublicLayoutComponent,
        children: [
            { path: '', component: LandingComponent },
            { path: 'sign-in', component: LoginComponent },
            { path: 'sign-up', component: SignupComponent },
            { path: 'verify/:verification_token', component: VerifyComponent },
        ]
    },
    {
        path: 'dashboard',
        canActivate: [
            AuthGuard
        ],
        component: DashboardLayoutComponent,
        children: [
            { path: '', component: DashboardComponent },
        ]
    },
    { path: 'not-found', component: NotFoundComponent },
    { path: '**', redirectTo: '/not-found' },
];
