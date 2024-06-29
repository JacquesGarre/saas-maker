import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiService } from '../../services/api.service';

@Component({
  selector: 'app-verify',
  standalone: true,
  imports: [],
  templateUrl: './verify.component.html',
  styleUrl: './verify.component.scss'
})
export class VerifyComponent {

  token: string | null;

  constructor(
    private route: ActivatedRoute, 
    private router: Router,
    private apiService: ApiService
  ){
    this.token = this.route.snapshot.paramMap.get('verification_token');   
    if (!this.token) {
      this.router.navigate(['/not-found']);
    }
  }


}
