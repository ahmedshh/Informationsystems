# E-Doc

E-Doc is a specialized platform designed for the medical community to manage patient records efficiently and facilitate online communication through video and chat. This platform is particularly suited for private doctors who need an all-in-one solution to handle their patient interactions and record-keeping seamlessly.

## Features

- **Patient Record Management:** Every Doctor keeps the electronic health records (EHR) for their patient separately.
- **Online Video and Chat:** Communicate with patients through video calls and chat (in progress).
- **User-Friendly Interface:** Easy to use for doctors.
- **Secure Data Handling:** Keeps all patient data safe and private.

## Benefits

- **Efficient Workflow:** Makes managing patient records and communication easy and quick.
- **Enhanced Patient Care:** Better patient engagement through online consultations.
- **Integrated Solution:** Everything you need in one platform, no need for multiple tools.

## Getting Started

### Using Docker

1. **Install Docker:** Make sure Docker is installed on your machine.
2. **Clone the Repository:** Clone the E-Doc repository from GitHub.
    ```
    git clone https://github.com/Abdul-Hannan-21/e-doc.git
    ```
3. **Navigate to the Directory:**
    ```
    cd e-doc
    ```
4. **Build and Run the Docker Container:**
    ```
    docker-compose up --build
    ```
5. **Access E-Doc:** Open your browser and go to `http://localhost:your-port` to access the platform.

### Using GitHub Codespaces

1. **Open Repository in Codespaces:** Go to the E-Doc repository on GitHub and click on the "Code" button, then select "Open with Codespaces".
2. **Setup Environment:** Codespaces will automatically set up the environment based on the repository configuration.
3. **Run E-Doc:** Follow the instructions provided in the repository to start the application within Codespaces.

## Troubleshooting

If you encounter any issues while using E-Doc, here are some steps you can take to troubleshoot:

### Application Not Loading Properly

- **Issue:** Sometimes the application may not load correctly on the first attempt.

- **Solution:** If you see an error message or the page isn’t loading as expected, try refreshing your browser (`F5` or `Ctrl + R`). This can often resolve temporary issues with the application loading process.

### Docker Container Issues

- **Issue:** Docker containers (`www`, `db`, `phpmyadmin`) not starting or encountering errors.

- **Solution:** Check the Docker logs to identify any errors:
  ```bash
  docker-compose logs



## Contribution

E-Doc was developed by:

- Abdul Mannan
- Abdul Hannan
- Yadwinder Singh
