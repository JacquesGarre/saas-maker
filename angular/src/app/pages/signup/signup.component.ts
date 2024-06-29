import { Component } from '@angular/core';
import { PublicNavbarComponent } from '../../components/public-navbar/public-navbar.component';
import { FormConfig } from '../../components/form/form-config.interface';
import { FormComponent } from '../../components/form/form.component';
import { CommonModule } from '@angular/common';
import { User } from '../../models/user.interface';
import { ApiService } from '../../services/api.service';
import { Observable } from 'rxjs';
import { v4 as uuidv4 } from 'uuid';

@Component({
  selector: 'app-signup',
  standalone: true,
  imports: [
    PublicNavbarComponent,
    FormComponent,
    CommonModule
  ],
  templateUrl: './signup.component.html',
  styleUrl: './signup.component.scss'
})
export class SignupComponent {

  constructor(private apiService: ApiService) {}

  signUpFormConfig: FormConfig = {
    submitAction: (user: User): Observable<any> => {
      user.id = user.id ?? uuidv4();
      return this.apiService.createUser(user);
    },
    afterSubmitRedirection: {
      route: '/sign-in', 
      extras: { 
        state: { 
          toasterConfig: {
            message: 'Your account has been successfully created. A verification email has been sent to your email address. Please check your inbox and follow the instructions to verify your account.',
            show: true,
            class: 'bottom-4 right-4 left-4 px-4 d-flex bg-green-400 text-center'
          }
        } 
      }
    },
    submitBtnLabel: 'Sign up',
    fields: [
      {
        formControlName: 'email',
        value: 'test@test.com',
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
        formControlName: 'firstName',
        value: 'test',
        type: 'text',
        label: 'First name',
        required: true,
        validators: [
          {
            type: 'required',
            errorMessage: 'First name is required'
          }
        ]
      },
      {
        formControlName: 'lastName',
        value: 'test',
        type: 'text',
        label: 'Last name',
        required: true,
        validators: [
          {
            type: 'required',
            errorMessage: 'Last name is required'
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
      },
      {
        formControlName: 'passwordConfirmation',
        value: 'p@ssw0rD',
        type: 'password',
        label: 'Password confirmation',
        required: true,
        validators: [
          {
            type: 'required',
            errorMessage: 'Password confirmation is required'
          },
          {
            type: 'match',
            value: 'password',
            errorMessage: 'Password confirmation should match your password'
          }
        ]
      }
    ]
  }
}

