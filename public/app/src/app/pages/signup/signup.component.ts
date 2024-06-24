import { Component } from '@angular/core';
import { PublicNavbarComponent } from '../../components/public-navbar/public-navbar.component';
import { FormConfig } from '../../components/form/form-config.interface';
import { FormComponent } from '../../components/form/form.component';
import { CommonModule } from '@angular/common';

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

  signUpFormConfig: FormConfig = {
    submitAction: this.submitAction,
    submitBtnLabel: 'Sign up',
    fields: [
      {
        formControlName: 'email',
        value: '',
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
        value: '',
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
        value: '',
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
        value: '',
        type: 'password',
        label: 'Password',
        required: true,
        validators: [
          {
            type: 'required',
            errorMessage: 'Password is required'
          },
          {
            type: 'minlength',
            errorMessage: 'Password must be at least 8 characters.',
            value: 8
          }
        ]
      },
      {
        formControlName: 'passwordConfirmation',
        value: '',
        type: 'password',
        label: 'Password confirmation',
        required: true,
        validators: [
          {
            type: 'required',
            errorMessage: 'Password confirmation is required'
          }
        ]
      }
    ]
  }

  submitAction(values: any): void {
    console.log('submitAction in signup component')
    console.log('values:', values)
  }
}

