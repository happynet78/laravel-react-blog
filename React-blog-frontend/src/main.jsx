import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import App from './App.jsx'
import { BrowserRouter } from 'react-router-dom'
import AppContext from './Context/AppContext.jsx'

createRoot(document.getElementById('root')).render(
    <AppContext>
        <App />
    </AppContext>
    // <StrictMode>
    //     <BrowserRouter>
    //         <App />
    //     </BrowserRouter>
    // </StrictMode>,
)
