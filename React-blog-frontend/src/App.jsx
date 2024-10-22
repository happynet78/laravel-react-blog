import { useContext, useState } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { Routes, Route, BrowserRouter } from 'react-router-dom';
import Blogs from './components/Blogs';
import CreateBlog from './components/CreateBlog';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import BlogDetail from './components/BlogDetail';
import EditBlog from './components/EditBlog';
import "./App.css";
import { AppContext } from './Context/AppContext';
import Layout from './pages/Layout';
import Home from './pages/Home';
import Register from './Auth/Register';
import Login from './Auth/Login';

function App() {
    const [count, setCount] = useState();
    const { user } = useContext(AppContext);

    return (
    <>
        {/* <div className='bg-dark text-center py-2 shadow-lg'>
            <h1 className='text-white'>React & Laravel Blog App</h1>
        </div> */}

        <BrowserRouter>
            <Routes>
                <Route path="/" element={ <Layout /> }>
                    <Route path="/" element={ <Home /> } />

                    <Route path="/register" element={user ? <Home /> : <Register />} />
                    <Route path="/login" element={user ? <Home /> : <Login />} />

                    <Route path="/blogs" element={ <Blogs  /> } />
                    <Route path="/create" element={ <CreateBlog /> } />
                    <Route path="/blog/:id" element={ <BlogDetail /> } />
                    <Route path="/blog/edit/:id" element={ <EditBlog /> } />

                </Route>
            </Routes>
        </BrowserRouter>
        
        <ToastContainer />


        
        
    </>
    )
}

export default App
