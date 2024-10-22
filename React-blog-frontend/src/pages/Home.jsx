import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import BlogCard from "../components/BlogCard";


export default function Home() {
    const  [posts, setPosts] = useState([]);

    const getPosts = async () => {
        const res = await fetch('/api/blogs');
        const data = await res.json();
console.log(data.data);
        if(res.status == 200) {
            setPosts(data.data);
        }
    }

    useEffect(() => {
        getPosts();
    }, []);

    return (
        <>
            <div className="container">
                
                <h1 className="title">Latest Posts</h1>
                <div className="row">
                {
                    (posts) && posts.map((post) => {
                        return (<BlogCard blogs={posts} setBlogs={setPosts} blog={post} key={post.id}/>)
                    })
                }
                </div>

            </div>
        </>
    );
}
