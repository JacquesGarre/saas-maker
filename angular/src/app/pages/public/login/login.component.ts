import { Component } from '@angular/core';
import { PublicNavbarComponent } from '../../../components/public-navbar/public-navbar.component';
import { Router } from '@angular/router';
import { ToasterConfig } from '../../../components/toaster/toaster-config.interface';
import { ToasterComponent } from '../../../components/toaster/toaster.component';
import { CommonModule } from '@angular/common';
import { FormConfig } from '../../../components/form/form-config.interface';
import { User } from '../../../models/user.interface';
import { Observable } from 'rxjs';
import { ApiService } from '../../../services/api.service';
import { FormComponent } from '../../../components/form/form.component';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [
    PublicNavbarComponent,
    ToasterComponent,
    CommonModule,
    FormComponent
  ],
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss'
})
export class LoginComponent {


  toasterConfig: ToasterConfig;

  loginFormConfig: FormConfig = {
    submitAction: (user: User): Observable<any> => {
      return this.apiService.loginUser(user);
    },
    afterSubmitAction: (response: any): Observable<any> => {
      let jwt = response.jwt;
      sessionStorage.setItem('jwt', jwt);
      return new Observable();
    },
    afterSubmitRedirection: {
      route: '/dashboard'
    },
    submitBtnLabel: 'Sign in',
    fields: [
      {
        formControlName: 'email',
        value: 'test123123@test.com',
        type: 'email',
        label: 'Email address',
        required: true,
        validators: [
          {
            type: 'required',
            errorMessage: 'Email address is required'
          },
          {
            type: 'pattern',
            errorMessage: 'Invalid email address',
            value: '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
          }
        ]
      },
      {
        formControlName: 'password',
        value: 'p@ssw0rD',
        type: 'password',
        label: 'Password',
        required: true,
        validators: [
          {
            type: 'required',
            errorMessage: 'Password is required'
          },
          {
            type: 'pattern',
            errorMessage: 'Password must have at least 1 uppercase letter, 1 lowercase letter, 1 digit and 1 special character',
            value: '^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[!@#$%^&*()-_=+<>?]).{1,}$'
          },
          {
            type: 'minlength',
            errorMessage: 'Password must be at least 8 characters',
            value: 8
          }
        ]
      }
    ]
  }

  constructor(private router: Router, private apiService: ApiService) {
    this.toasterConfig = this.router.getCurrentNavigation()?.extras?.state?.['toasterConfig'] ?? null;
  }
}
