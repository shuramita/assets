import { environment } from '../environments/environment';

export class AppConstant {
    static API_HOST = environment.api;
    static API_PREFIX = environment.apiPrefix;
    static API_KEY = environment.localApiKey;
    static TIMEOUT_REQUEST =  5000;
}
