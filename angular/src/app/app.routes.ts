import { Routes } from '@angular/router';
import { LandingComponent } from './pages/landing/landing.component';
import { LoginComponent } from './pages/login/login.component';
import { SignupComponent } from './pages/signup/signup.component';
import { NotFoundComponent } from './pages/not-found/not-found.component';
import { PublicLayoutComponent } from './layouts/public-layout/public-layout.component';

export const routes: Routes = [
    {
        path: '',
        component: PublicLayoutComponent,
        children: [
            { path: '', component: LandingComponent },
            { path: 'login', component: LoginComponent },
            { path: 'sign-up', component: SignupComponent },
            { path: 'not-found', component: NotFoundComponent },
            { path: '**', redirectTo: '/not-found' },
        ]
    }
];
