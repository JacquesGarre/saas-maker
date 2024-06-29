import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiService } from '../../services/api.service';
import { ToasterConfig } from '../../components/toaster/toaster-config.interface';
import { ToasterComponent } from '../../components/toaster/toaster.component';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-verify',
  standalone: true,
  imports: [
    ToasterComponent,
    CommonModule
  ],
  templateUrl: './verify.component.html',
  styleUrl: './verify.component.scss'
})
export class VerifyComponent {

  token: string;
  toasterConfig!: ToasterConfig;
  secondsUntilRedirection: number = 5;
  error!: string;

  constructor(
    private route: ActivatedRoute, 
    private router: Router,
    private apiService: ApiService
  ){
    this.token = this.route.snapshot.paramMap.get('verification_token') ?? '';   
    if (!this.token) {
      this.router.navigate(['/not-found']);
    }
  }

  ngOnInit(): void
  {
    this.apiService.verifyUser(this.token).subscribe({
      next: (response: any) => {
        this.toasterConfig = {
          message: response.message ?? 'Your email address has been verified',
          show: true,
          class: 'bottom-4 right-4 max-w-xs w-full bg-green-400'
        }  
      },
      error: (error: any) => {
        this.error = error.error.message;
        this.toasterConfig = {
          message: error.error.message ?? 'Your email address has been verified',
          show: true,
          class: 'bottom-4 right-4 max-w-xs w-full bg-red-400'
        }  
      }
    });
    let that = this; 
    setInterval(function() {
      that.secondsUntilRedirection -= 1;
      if(that.secondsUntilRedirection == 0){
        that.router.navigate(['/sign-in']);
      }
    }, 1000)
  }
}
