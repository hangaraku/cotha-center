# Student Projects API Documentation

## Authentication
**API Key Required:** `cothacotha`

Include the API key in one of the following ways:
- **Header:** `X-API-Key: cothacotha`
- **Query Parameter:** `?api_key=cothacotha`

---

## Endpoints

### 1. Get All Student Projects
**GET** `/api/student-projects`

**Query Parameters:**
- `user_id` (optional) - Filter by user ID
- `type` (optional) - Filter by project type (e.g., "Scratch")
- `api_key` (optional) - API key if not using header

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "type": "Scratch",
      "url": "https://scratch.mit.edu/projects/123456",
      "title": "My Project",
      "description": "Project description",
      "thumbnail": "http://domain.com/uploads/thumbnail.jpg",
      "views": 100,
      "score": 50,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "profile_picture": "http://domain.com/uploads/profile.jpg",
        "school": "ABC Elementary School",
        "age": 12
      },
      "module": {
        "id": 1,
        "name": "Module Name",
        "description": "Module description",
        "img_url": "module.jpg",
        "level": {
          "id": 1,
          "name": "Beginner"
        }
      },
      "interactions": {
        "likes_count": 10,
        "loves_count": 5,
        "stars_count": 3,
        "total_count": 18
      },
      "interactions_detail": [
        {
          "id": 1,
          "type": "like",
          "user": {
            "id": 2,
            "name": "Jane Doe",
            "profile_picture": "http://domain.com/uploads/profile2.jpg"
          },
          "created_at": "2024-01-01T00:00:00.000000Z"
        }
      ]
    }
  ],
  "count": 1
}
```

---

### 2. Get Single Student Project
**GET** `/api/student-projects/{id}`

**Path Parameters:**
- `id` - Project ID

**Query Parameters:**
- `api_key` (optional) - API key if not using header

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "type": "Scratch",
    "url": "https://scratch.mit.edu/projects/123456",
    "title": "My Project",
    "description": "Project description",
    "thumbnail": "http://domain.com/uploads/thumbnail.jpg",
    "views": 100,
    "score": 50,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "profile_picture": "http://domain.com/uploads/profile.jpg",
      "school": "ABC Elementary School",
      "age": 12
    },
    "module": {
      "id": 1,
      "name": "Module Name",
      "description": "Module description",
      "img_url": "module.jpg",
      "level": {
        "id": 1,
        "name": "Beginner"
      }
    },
    "interactions": {
      "likes_count": 10,
      "loves_count": 5,
      "stars_count": 3,
      "total_count": 18
    },
    "interactions_detail": [
      {
        "id": 1,
        "type": "like",
        "user": {
          "id": 2,
          "name": "Jane Doe",
          "profile_picture": "http://domain.com/uploads/profile2.jpg"
        },
        "created_at": "2024-01-01T00:00:00.000000Z"
      }
    ]
  }
}
```

---

## Error Response
```json
{
  "success": false,
  "message": "Invalid or missing API key"
}
```
**Status Code:** 401 Unauthorized

---

## Example Requests

**cURL:**
```bash
# Using header
curl -H "X-API-Key: cothacotha" https://domain.com/api/student-projects

# Using query parameter
curl https://domain.com/api/student-projects?api_key=cothacotha

# With filters
curl -H "X-API-Key: cothacotha" https://domain.com/api/student-projects?user_id=1&type=Scratch
```

**JavaScript (Fetch):**
```javascript
fetch('https://domain.com/api/student-projects', {
  headers: {
    'X-API-Key': 'cothacotha'
  }
})
```

