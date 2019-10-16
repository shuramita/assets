export interface GlobalData {
    organizations?: Array<Organization>;
    organization?: Organization;
    asset_types?: any;
    locations?: any;
    venue_types?: any;
    events?: any;
}

export interface Organization {
    id: number;
    name: string;
    slug: string;
    location: string;
    currency: string;
    language: string;
    timezone: string;
    industry: string;
    client_portal_url: string;
    logo: number;
    address: string;
    street2: string;
    city: string;
    state: string;
    country: string;
    code: string;
    phone: string;
    fax: string;
    category_id: number;
    description: string;
    extra_fields: string;
    created_by: string;
    created_at: string;
    updated_at: string;
    building: Building;
    buildings: Building[];
    prices: Price[];
    fields: Field[];
    taxes: Tax[];
}

export interface Building {
    id: number;
    name: string;
    slug: string;
    logo_id: number;
    category_id: number;
    building_type: string;
    address: string;
    phone_country: string;
    phone_number: string;
    email: string;
    website: string;
    description: string;
    background_photo: number;
    organization_id: number;
    created_at: string;
    updated_at: string;
    floors: Floor[];
}

export interface Price {
    'id': number;
    'name': string;
    'type': string;
    'unit': string;
    'minimum_by_unit': number;
    'available_at': string;
    'description': string;
    'priority': number;
    'organization_id': number;
    'created_at': string;
    'updated_at': string;
    disabled?: boolean;
}

export interface Field {
    'id': number;
    'key': string;
    'value': string;
    'model': string;
    'group': string;
    'title': string;
    'description': string;
    'type': string;
    'organization_id': number;
    'order': number;
    'created_at': string;
    'updated_at': string;
}

export interface Tax {
    'id': number;
    'key': string;
    'value': string;
    'model': string;
    'group': string;
    'title': string;
    'description': string;
    'type': string;
    'organization_id': number;
    'order': number;
    'created_at': string;
    'updated_at': string;
}

export interface Floor {
    'id': number;
    'name': string;
    'code': string;
    'building_id': number;
    'photo_id': number;
    'map_id': number;
    'description': string;
    'created_at': string;
    'updated_at': string;
}
export interface Customer {
    'id': number;
    'name': string;
    'email': string;
    'created_at': string;
    'updated_at': string;
}
