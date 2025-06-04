import Header from "./components/Header";
import Link from "next/link";
import Image from "next/image";

export default function Home() {
  return (
    <div className="min-h-screen flex flex-col">
      <Header />
      
      <main className="flex-grow">
        {/* Hero Section */}
        <div className="bg-indigo-700 text-white">
          <div className="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center">
            <div className="lg:w-1/2 mb-8 lg:mb-0">
              <h1 className="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                Book your medical appointments online
              </h1>
              <p className="mt-6 text-xl max-w-3xl">
                Browse through our services, choose the one that suits your needs, and book your appointment in just a few clicks.
              </p>
              <div className="mt-10 flex space-x-4">
                <Link
                  href="/offers"
                  className="bg-white text-indigo-600 border border-transparent rounded-md px-5 py-3 text-base font-medium hover:bg-indigo-50"
                >
                  Browse Services
                </Link>
                <Link
                  href="/register"
                  className="bg-indigo-800 border border-transparent rounded-md px-5 py-3 text-base font-medium text-white hover:bg-indigo-900"
                >
                  Sign up
                </Link>
              </div>
            </div>
            <div className="lg:w-1/2 flex justify-center">
              <div className="relative w-full h-64 sm:h-72 md:h-96 lg:h-[450px]">
                <Image
                  src="/medical-appointment.png"
                  alt="Medical appointment"
                  fill
                  style={{ objectFit: "cover" }}
                  className="rounded-lg shadow-xl"
                  priority
                />
              </div>
            </div>
          </div>
        </div>
        
        {/* Features Section */}
        <div className="bg-white py-12">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="lg:text-center">
              <h2 className="text-base text-indigo-600 font-semibold tracking-wide uppercase">Features</h2>
              <p className="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                A better way to book medical services
              </p>
              <p className="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                Our platform makes it easy to find and book the medical services you need.
              </p>
            </div>

            <div className="mt-10">
              <div className="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                <div className="bg-gray-50 p-6 rounded-lg shadow">
                  <h3 className="text-lg font-medium text-gray-900">Easy Booking</h3>
                  <p className="mt-2 text-base text-gray-500">
                    Book your appointment with just a few clicks, anytime, anywhere.
                  </p>
                </div>

                <div className="bg-gray-50 p-6 rounded-lg shadow">
                  <h3 className="text-lg font-medium text-gray-900">Wide Range of Services</h3>
                  <p className="mt-2 text-base text-gray-500">
                    Find the right medical service for your specific needs.
                  </p>
                </div>

                <div className="bg-gray-50 p-6 rounded-lg shadow">
                  <h3 className="text-lg font-medium text-gray-900">Secure Payments</h3>
                  <p className="mt-2 text-base text-gray-500">
                    Pay for your appointments securely through our platform.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
      
    
    </div>
  );
}
